<?php

namespace App\Presentation\Api\V1\Invoice;

use App\Core\Base\Presentation\BaseApiEntityPresenter;
use App\Core\Dto\Invoice\InvoiceDto;
use App\Core\Enum\InvoiceStatus;
use App\Core\Enum\InvoiceStatusLogicException;
use App\Core\Response\Invoice\InvoiceLogsResponse;
use App\Core\Response\Invoice\InvoiceResponse;
use App\Core\Service\RabbitMqService;
use App\Database\Entity\Invoice\Invoice;
use App\Service\Invoice\InvoiceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ValueError;

/**
 * API Invoice Presenter.
 */
class InvoicePresenter extends BaseApiEntityPresenter
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator,
        protected InvoiceService $invoiceService
    ) {
        parent::__construct($em, $serializer, $validator);
    }

    /**
     * @inheritDoc
     */
    protected function getEntity(): string
    {
        return Invoice::class;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityDto(): string
    {
        return InvoiceDto::class;
    }

    /**
     * @inheritDoc
     */
    protected function getList(): mixed
    {
        $repository = $this->getEntityRepository();

        $entities = $repository->findAll();

        $result = [];

        foreach ($entities as $entity) {
            $result[] = new InvoiceResponse($entity);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    protected function getDetail(): mixed
    {
        $repository = $this->getEntityRepository();

        $entity = $repository->find($this->id);

        if ($entity === null) {
            $this->sendJsonError('Entity not found');
        }

        return new InvoiceResponse($entity);
    }

    /**
     * @inheritDoc
     */
    protected function create(array $data): mixed
    {
        $dto = $this->serializer->denormalize($data, $this->getEntityDto());
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->sendJsonError($this->serializer->normalize($errors));
        }

        /**
         * TODO publish a new message to RabbitMqService for async processing
         *
         * @see RabbitMqService
         */
        $invoice = $this->invoiceService->createFromDto($dto);

        return new InvoiceResponse($invoice);
    }

    /**
     * @inheritDoc
     */
    protected function update(array $data): mixed
    {
        $dto = $this->serializer->denormalize($data, $this->getEntityDto());
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->sendJsonError($this->serializer->normalize($errors));
        }

        $invoiceRepository = $this->em->getRepository($this->getEntity());
        $invoice = $invoiceRepository->find($this->id);

        if (!$invoice) {
            $this->sendJsonError('Entity not found');
        }

        /**
         * TODO publish a new message to RabbitMqService for async processing
         *
         * @see RabbitMqService
         */
        $invoice = $this->invoiceService->updateFromDto($invoice, $dto);

        return new InvoiceResponse($invoice);
    }

    /**
     * @inheritDoc
     */
    protected function delete(): mixed
    {
        return false;
    }

    /**
     * Actions for invoice status change.
     *
     * @return void
     */
    public function actionStatus(): void
    {
        if ($this->getRequest()->getMethod() !== 'PUT') {
            $this->sendJsonError('Method Not Allowed');
        }

        $data = $this->getData();

        $id = $this->getParameter('id');
        $invoiceRepository = $this->em->getRepository($this->getEntity());
        $invoice = $invoiceRepository->find($id);

        if (!$invoice) {
            $this->sendJsonError('Entity not found');
        }

        try {
            $invoiceStatus = InvoiceStatus::from($data['status']);
        } catch (ValueError) {
            $this->sendJsonError('Invalid status ' . $data['status']);
        }

        try {
            /**
             * TODO publish a new message to RabbitMqService for async processing
             *
             * @see RabbitMqService
             */
            $invoice = $this->invoiceService->updateInvoiceStatus($invoice, $invoiceStatus);
        } catch (InvoiceStatusLogicException $e) {
            $this->sendJsonError($e->getMessage());
        }

        $this->sendJson(new InvoiceResponse($invoice));
    }

    /**
     * Action for invoice logs.
     *
     * @return void
     */
    public function actionLogs(): void
    {
        $id = $this->getParameter('id');

        $invoiceRepository = $this->em->getRepository($this->getEntity());
        $invoice = $invoiceRepository->find($id);

        $this->sendJson(new InvoiceLogsResponse($invoice));
    }
}

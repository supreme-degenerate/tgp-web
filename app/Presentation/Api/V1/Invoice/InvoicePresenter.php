<?php

namespace App\Presentation\Api\V1\Invoice;

use App\Core\Base\Presentation\BaseApiEntityPresenter;
use App\Core\Dto\Invoice\InvoiceDto;
use App\Core\Enum\InvoiceStatus;
use App\Core\Response\Invoice\InvoiceResponse;
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
    protected function create(array $data): mixed
    {
        $dto = $this->serializer->denormalize($data, $this->getEntityDto());
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $this->sendJson([
                'errors' => $this->serializer->normalize($errors),
            ]);
        }

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
            $this->sendJson([
                'errors' => $this->serializer->normalize($errors),
            ]);
        }

        $invoiceRepository = $this->em->getRepository($this->getEntity());
        $invoice = $invoiceRepository->find($this->id);

        if (!$invoice) {
            $this->error('Entity not found', 404);
        }

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
     * @inheritDoc
     */
    public function actionStatus(): void
    {
        if ($this->getRequest()->getMethod() !== 'PUT') {
            $this->error('Method Not Allowed', 405);
        }

        $data = $this->getData();

        $id = $this->getParameter('id');
        $invoiceRepository = $this->em->getRepository($this->getEntity());
        $invoice = $invoiceRepository->find($id);

        if (!$invoice) {
            $this->error('Entity not found', 404);
        }

        try {
            $invoiceStatus = InvoiceStatus::from($data['status']);
        } catch (ValueError $e) {
            $this->error('Invalid status', 400);
        }

        $invoice = $this->invoiceService->updateInvoiceStatus($invoice, $invoiceStatus);

        $this->sendJson(new InvoiceResponse($invoice));
    }
}

<?php

namespace App\Core\Base\Presentation;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Nette\Application\BadRequestException;
use Nette\Utils\Json;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseApiPresenter extends BasePresenter
{
    /** @var ?int $id ID of the entity */
    protected ?int $id;

    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    /**
     * Default API action.
     *
     * TODO ID handling should be alterable
     *
     * @return void
     *
     * @throws ApiPresenterMethodNotImplemented
     * @throws ApiUnsupportedMethod
     */
    public function actionDefault(): void
    {
        $method = $this->getRequest()->getMethod();
        // TODO custom id names
        $this->id = $this->getParameter('id');

        try {
            switch (strtoupper($method)) {
                case 'GET':
                    $this->actionGet();
                    break;
                case 'POST':
                    $this->actionPost();
                    break;
                case 'PUT':
                    $this->actionPut();
                    break;
                case 'DELETE':
                    $this->actionDelete();
                    break;
            }

            throw new ApiUnsupportedMethod($method);
        } catch (ApiPresenterMethodNotImplemented|BadRequestException $e) {
            $this->sendJsonError($e->getFile() . ': ' . $e->getMessage());
        }
    }

    /**
     * Action handling GET request.
     *
     * @return void
     */
    public function actionGet(): void
    {
        throw new ApiPresenterMethodNotImplemented('GET not implemented');
    }

    /**
     * Action handling POST request.
     *
     * @return void
     *
     * @throws ApiPresenterMethodNotImplemented
     */
    public function actionPost(): void
    {
        throw new ApiPresenterMethodNotImplemented('POST not implemented');
    }

    /**
     * Action handling PUT request.
     *
     * @return void
     *
     * @throws ApiPresenterMethodNotImplemented
     */
    public function actionPut(): void
    {
        throw new ApiPresenterMethodNotImplemented('PUT not implemented');
    }

    /**
     * Action handling DELETE request.
     *
     * @return void
     *
     * @throws ApiPresenterMethodNotImplemented
     */
    public function actionDelete(): void
    {
        throw new ApiPresenterMethodNotImplemented('DELETE not implemented');
    }

    /**
     * Returns HTTP's JSON data.
     *
     * @return array
     */
    protected function getData(): array
    {
        return Json::decode($this->getHttpRequest()->getRawBody(), true);
    }

    /**
     * Sends JSON response with an error.
     *
     * @param string|array $error
     *
     * @return void
     */
    protected function sendJsonError(string|array $error): void
    {
        if (is_array($error)) {
            $this->sendJson(['errors' => $error]);
        }

        $this->sendJson(['error' => $error]);
    }
}

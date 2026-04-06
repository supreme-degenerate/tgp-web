<?php

namespace App\Core\Base\Presentation;

abstract class BaseApiPresenter extends BasePresenter
{
    /** @var ?int $id ID of the entity */
    protected ?int $id;

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

        switch (strtoupper($method)) {
            case 'GET':
                $this->actionGet();
                break;
            case 'POST':
                $this->actionPost();
                break;
            case 'UPDATE':
                $this->actionUpdate();
                break;
            case 'DELETE':
                $this->actionDelete();
                break;
        }

        throw new ApiUnsupportedMethod($method);
    }

    /**
     * Action handling GET request.
     *
     * @return void
     */
    public function actionGet(): void
    {
        throw new ApiPresenterMethodNotImplemented('GET');
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
        throw new ApiPresenterMethodNotImplemented('POST');
    }

    /**
     * Action handling UPDATE request.
     *
     * @return void
     *
     * @throws ApiPresenterMethodNotImplemented
     */
    public function actionUpdate(): void
    {
        throw new ApiPresenterMethodNotImplemented('UPDATE');
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
        throw new ApiPresenterMethodNotImplemented('DELETE');
    }
}

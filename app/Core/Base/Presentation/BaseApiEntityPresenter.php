<?php

namespace App\Core\Base\Presentation;

use Doctrine\ORM\EntityRepository;

abstract class BaseApiEntityPresenter extends BaseApiPresenter
{
    /**
     * Returns an API entity.
     *
     * @return class-string
     */
    abstract protected function getEntity(): string;

    /**
     * Returns an API entity repository.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntity());
    }

    /**
     * Action handling GET request.
     *
     * @return void
     */
    public function actionGet(): void
    {
        $repository = $this->getEntityRepository();

        if ($this->id) {
            $entity = $repository->find($this->id);

            if ($entity === null) {
                $this->error('Entity not found', 404);
            }

            $this->sendJson(
                $this->serializer->normalize($entity)
            );
        }

        $this->sendJson(
            $this->serializer->normalize($repository->findAll())
        );
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

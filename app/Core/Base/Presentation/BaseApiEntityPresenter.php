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
     * Returns an API entity DTO.
     *
     * @return class-string
     */
    abstract protected function getEntityDto(): string;

    /**
     * Returns an API entity repository.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->em->getRepository($this->getEntity());
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function actionPost(): void
    {
        $data = $this->getData();

        if (!$data) {
            $this->error('No data provided', 400);
        }

        $this->sendJson($this->create($data));
    }

    /**
     * Handles create API logic.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected abstract function create(array $data): mixed;

    /**
     * @inheritDoc
     */
    public function actionPut(): void
    {
        $data = $this->getData();

        if (!$data) {
            $this->error('No data provided', 400);
        }

        $entity = $this->update($data);

        $this->sendJson(
            $this->serializer->normalize($entity)
        );
    }

    /**
     * Handles update API logic.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected abstract function update(array $data): mixed;

    /**
     * @inheritDoc
     */
    public function actionDelete(): void
    {
        $this->sendJson([
            'success' => $this->delete(),
        ]);
    }

    /**
     * Handles update delete logic.
     *
     * @return mixed
     */
    protected abstract function delete(): mixed;
}

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
        if ($this->id) {
            $this->sendJson($this->getDetail());
        }

        $this->sendJson($this->getList());
    }

    /**
     * Handles get list API logic.
     *
     * @return mixed
     */
    protected abstract function getList(): mixed;

    /**
     * Handles get detail API logic.
     *
     * @return mixed
     */
    protected abstract function getDetail(): mixed;

    /**
     * @inheritDoc
     */
    public function actionPost(): void
    {
        $data = $this->getData();

        if (!$data) {
            $this->sendJsonError('No data provided');
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
            $this->sendJsonError('No data provided');
        }

        $this->sendJson(
            $this->update($data)
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

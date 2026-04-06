<?php

namespace App\Core\Base\Presentation;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\UI\Presenter;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BasePresenter extends Presenter
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer
    ) {
        parent::__construct();
    }
}

<?php

namespace App\Core\Base\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class BaseRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, string $entityClass)
    {
        $classMetadata = $em->getClassMetadata($entityClass);

        parent::__construct($em, $classMetadata);
    }
}

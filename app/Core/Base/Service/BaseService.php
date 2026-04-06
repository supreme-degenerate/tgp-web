<?php

namespace App\Core\Base\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseService
{
    public function __construct(protected EntityManagerInterface $em)
    {
    }
}

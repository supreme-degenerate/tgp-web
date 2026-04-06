<?php

namespace App\Database\Repository\Invoice;

use App\Core\Base\Database\BaseRepository;
use App\Database\Entity\Invoice\Invoice;
use App\Database\Entity\Invoice\InvoiceNumberSequence;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceNumberSequenceRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Invoice::class);
    }

    public function findOneByYear(int $year): ?InvoiceNumberSequence
    {
        return $this->findOneBy(['year' => $year]);
    }
}

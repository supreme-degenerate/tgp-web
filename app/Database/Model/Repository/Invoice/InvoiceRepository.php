<?php

namespace App\Database\Model\Repository\Invoice;

use App\Core\Base\Repository\BaseRepository;
use App\Database\Entity\Invoice\Invoice;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Invoice::class);
    }

    public function findPaid(): array
    {
        return $this->findBy(['status' => 'paid']);
    }

    public function findByNumber(string $number): ?Invoice
    {
        return $this->findOneBy(['number' => $number]);
    }
}

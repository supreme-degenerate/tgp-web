<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'invoice_items')]
class InvoiceNumberSequence extends BaseEntity
{
    #[ORM\Column(type: 'integer')]
    protected int $year;

    #[ORM\Column(type: 'integer')]
    protected int $number;
}

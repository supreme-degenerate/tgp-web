<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use App\Database\Model\Repository\Invoice\InvoiceInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'invoice_items')]
class InvoiceItem extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'integer')]
    protected int $invoiceId;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    protected float $pricePerUnit;

    #[ORM\Column(type: 'string', length: 3)]
    protected string $currency;

    #[ORM\Column(type: 'integer')]
    protected int $quantity;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    protected float $totalPrice;

    #[ORM\ManyToOne(targetEntity: InvoiceInterface::class)]
    protected InvoiceInterface $invoice;
}

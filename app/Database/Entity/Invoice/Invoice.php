<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use App\Database\Model\Repository\Invoice\InvoiceInterface;
use App\Database\Model\Repository\Invoice\InvoiceRepository;
use App\Database\Model\Repository\Invoice\InvoiceStatus;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: 'invoices')]
class Invoice extends BaseEntity implements InvoiceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 10)]
    protected string $invoiceNumber;

    #[ORM\Column(type: 'integer', enumType: InvoiceStatus::class)]
    protected InvoiceStatus $status;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $dueDate;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $raisedAt;

    #[ORM\Column(type: 'integer')]
    protected int $raisedBy;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getStatus(): int
    {
        return $this->status->value;
    }

    public function getStatusName(): string
    {
        return $this->status->getLabel();
    }
}

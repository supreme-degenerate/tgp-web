<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseAuditLog;
use App\Database\Model\Repository\Invoice\InvoiceInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'invoice_audit_logs')]
class InvoiceAuditLog extends BaseAuditLog
{
    #[ORM\Column(type: 'integer')]
    protected int $invoiceId;

    #[ORM\ManyToOne(targetEntity: InvoiceInterface::class)]
    protected InvoiceInterface $invoice;
}

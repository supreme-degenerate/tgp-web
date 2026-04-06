<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseAuditLog;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoice_audit_logs')]
class InvoiceAuditLog extends BaseAuditLog
{
    #[ORM\ManyToOne(targetEntity: Invoice::class)]
    #[ORM\JoinColumn(name: 'invoice_id', referencedColumnName: 'id')]
    protected Invoice $invoice;

    // Getters

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    // Setters

    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }
}

<?php

namespace App\Core\Response\Invoice;

use App\Core\Base\Response\BaseResponse;
use App\Database\Entity\Invoice\Invoice;

final class InvoiceResponse extends BaseResponse
{
    public int $id;
    public string $invoiceNumber;
    public int $status;
    public string $statusName;
    public string $dueDate;
    public string $raisedAt;
    public array $items = [];

    public function __construct(Invoice $invoice)
    {
        $this->id = $invoice->getId();
        $this->invoiceNumber = $invoice->getInvoiceNumber();
        $this->status = $invoice->getStatus()->value;
        $this->statusName = $invoice->getStatusName();
        $this->dueDate = $invoice->getDueDate()->format('Y-m-d');
        $this->raisedAt = $invoice->getRaisedAt()->format('Y-m-d H:i:s');

        foreach ($invoice->getItems() as $item) {
            $this->items[] = new InvoiceItemResponse($item);
        }
    }
}

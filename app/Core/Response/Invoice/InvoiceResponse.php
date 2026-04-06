<?php

namespace App\Core\Response\Invoice;

use App\Core\Base\Response\BaseResponse;
use App\Core\Response\Address\AddressResponse;
use App\Database\Entity\Invoice\Invoice;

final class InvoiceResponse extends BaseResponse
{
    public int $id;
    public string $invoiceNumber;
    public int $status;
    public string $statusName;
    public AddressResponse $shippingAddress;
    public AddressResponse $billingAddress;
    public string $dueDate;
    public string $raisedAt;
    public int $raisedBy;
    public array $items = [];

    public function __construct(Invoice $invoice)
    {
        $this->id = $invoice->getId();
        $this->invoiceNumber = $invoice->getInvoiceNumber();
        $this->status = $invoice->getStatus()->value;
        $this->statusName = $invoice->getStatusName();
        $this->shippingAddress = new AddressResponse($invoice->getShippingAddress());
        $this->billingAddress = new AddressResponse($invoice->getBillingAddress());
        $this->dueDate = $invoice->getDueDate()->format('Y-m-d');
        $this->raisedAt = $invoice->getRaisedAt()->format('Y-m-d H:i:s');
        $this->raisedBy = $invoice->getRaisedBy();

        foreach ($invoice->getItems() as $item) {
            $this->items[] = new InvoiceItemResponse($item);
        }
    }
}

<?php

namespace App\Service\Invoice;

use App\Core\Base\Service\BaseService;
use App\Core\Dto\Invoice\InvoiceDto;
use App\Core\Enum\InvoiceStatus;
use App\Database\Entity\Invoice\Invoice;
use App\Database\Entity\Invoice\InvoiceItem;
use DateTime;

class InvoiceService extends BaseService
{
    /**
     * Creates an invoice from DTO.
     *
     * @param InvoiceDto $invoiceDto
     *
     * @return Invoice
     */
    public function createFromDto(InvoiceDto $invoiceDto): Invoice
    {
        $invoice = new Invoice();
        $invoice->setStatus(InvoiceStatus::CREATED);
        $invoice->setDueDate(new DateTime($invoiceDto->dueDate));
        $invoice->setRaisedAt(new DateTime());
        $invoice->setRaisedBy(1); // TODO auth ID

        foreach ($invoiceDto->items as $itemDto) {
            $item = new InvoiceItem();
            $item->setName($itemDto->name);
            $item->setPricePerUnit($itemDto->pricePerUnit);
            $item->setCurrency($itemDto->currency);
            $item->setQuantity($itemDto->quantity);
            $item->setTotalPrice($itemDto->pricePerUnit * $itemDto->quantity);

            $invoice->addItem($item);
        }

        $this->em->persist($invoice);
        $this->em->flush();

        return $invoice;
    }

    /**
     * Updates an invoice from DTO.
     *
     * @param Invoice $invoice
     * @param InvoiceDto $invoiceDto
     *
     * @return Invoice
     */
    public function updateFromDto(Invoice $invoice, InvoiceDto $invoiceDto): Invoice
    {
        $invoice->setDueDate(new DateTime($invoiceDto->dueDate));

        // TODO update invoice items
        $this->em->flush();

        return $invoice;
    }

    /**
     * Updates invoice status.
     *
     * @param Invoice $invoice
     * @param InvoiceStatus $status
     *
     * @return Invoice
     */
    public function updateInvoiceStatus(Invoice $invoice, InvoiceStatus $status): Invoice
    {
        $invoice->setStatus($status);

        $this->em->flush();

        return $invoice;
    }
}

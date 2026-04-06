<?php

namespace App\Service\Invoice;

use App\Core\Base\Service\BaseService;
use App\Core\Dto\Invoice\InvoiceDto;
use App\Core\Enum\InvoiceStatus;
use App\Database\Entity\Address\Address;
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
        $shippingAddress = (new Address())
            ->setAddressLine1($invoiceDto->shippingAddress->addressLine1)
            ->setAddressLine2($invoiceDto->shippingAddress->addressLine2 ?? null)
            ->setCity($invoiceDto->shippingAddress->city)
            ->setState($invoiceDto->shippingAddress->state ?? null)
            ->setPostalCode($invoiceDto->shippingAddress->postalCode)
            ->setCountry($invoiceDto->shippingAddress->country);

        $billingAddress = (new Address())
            ->setAddressLine1($invoiceDto->billingAddress->addressLine1)
            ->setAddressLine2($invoiceDto->billingAddress->addressLine2 ?? null)
            ->setCity($invoiceDto->billingAddress->city)
            ->setState($invoiceDto->billingAddress->state ?? null)
            ->setPostalCode($invoiceDto->billingAddress->postalCode)
            ->setCountry($invoiceDto->billingAddress->country);

        $invoice = (new Invoice())
            ->setStatus(InvoiceStatus::CREATED)
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setDueDate(new DateTime($invoiceDto->dueDate))
            ->setRaisedAt(new DateTime())
            ->setRaisedBy(1);

        foreach ($invoiceDto->items as $itemDto) {
            $item = (new InvoiceItem())
                ->setName($itemDto->name)
                ->setPricePerUnit($itemDto->pricePerUnit)
                ->setCurrency($itemDto->currency)
                ->setQuantity($itemDto->quantity)
                ->setTotalPrice($itemDto->pricePerUnit * $itemDto->quantity);

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
        // TODO update billing & shipping address
        // ...

        $invoice->setDueDate(new DateTime($invoiceDto->dueDate));

        // TODO update invoice items
        // ...

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

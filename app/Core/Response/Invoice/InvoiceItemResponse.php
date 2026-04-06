<?php

namespace App\Core\Response\Invoice;

use App\Core\Base\Response\BaseResponse;
use App\Database\Entity\Invoice\InvoiceItem;

final class InvoiceItemResponse extends BaseResponse
{
    public int $id;
    public string $name;
    public float $pricePerUnit;
    public float $quantity;
    public float $totalPrice;
    public string $currency;

    public function __construct(InvoiceItem $item)
    {
        $this->id = $item->getId();
        $this->name = $item->getName();
        $this->pricePerUnit = $item->getPricePerUnit();
        $this->quantity = $item->getQuantity();
        $this->totalPrice = $item->getTotalPrice();
        $this->currency = $item->getCurrency();
    }
}

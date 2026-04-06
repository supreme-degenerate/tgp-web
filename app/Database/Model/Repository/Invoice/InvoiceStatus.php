<?php

namespace App\Database\Model\Repository\Invoice;

enum InvoiceStatus: int
{
    case CREATED = 1;
    case PAID = 2;
    case CANCELLED = 3;
    case REFUNDED = 4;

    /**
     * Returns an invoice status label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }
}

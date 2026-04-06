<?php

namespace App\Core\Enum;

enum InvoiceStatus: int
{
    case CREATED = 1;
    case PAID = 2;
    case CANCELED = 3;
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
            self::CANCELED => 'Canceled',
            self::REFUNDED => 'Refunded',
        };
    }
}

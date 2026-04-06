<?php

namespace App\Core\Enum;

enum InvoiceStatus: int
{
    case CREATED = 1;
    case PAID = 2;
    case CANCELLED = 3;
    case REFUNDED = 4;

    public const ALLOWED_TRANSITIONS = [
        self::CREATED->value => [self::PAID->value, self::CANCELLED->value],
        self::PAID->value => [self::REFUNDED->value],
        self::CANCELLED->value => [],
        self::REFUNDED->value => [],
    ];

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

    /**
     * Checks if the current invoice status can change to a new one.
     *
     * @param InvoiceStatus $newStatus
     *
     * @return bool
     */
    public function canChangeTo(self $newStatus): bool
    {
        if (!in_array($newStatus->value, self::ALLOWED_TRANSITIONS[$this->value], true)) {
            return false;
        }

        return true;
    }
}

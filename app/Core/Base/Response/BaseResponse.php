<?php

namespace App\Core\Base\Response;

use BackedEnum;
use DateTime;
use DateTimeImmutable;

abstract class BaseResponse
{
    /**
     * Converts mixed value to string safely.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function convertMixedValueToString(mixed $value): string
    {
        if (is_bool($value)) {
            $value = (int) $value;
        } elseif ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
            $value = $value->format('Y-m-d H:i:s');
        } elseif ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        return $value;
    }
}

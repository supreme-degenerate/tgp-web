<?php

namespace App\Core\Base\Database;

use DateTime;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use ReflectionMethod;

abstract class BaseEntity
{
    /**
     * Populates an entity with given data.
     *
     * @param array $data
     *
     * @return $this
     */
    public function populate(array $data): self
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            $setter = 'set' . ucfirst($key);

            if (!method_exists($this, $setter)) {
                continue;
            }

            $reflection = new ReflectionMethod($this, $setter);
            $parameters = $reflection->getParameters();

            if (empty($parameters)) {
                $this->$setter($value);
                continue;
            }

            $type = $parameters[0]->getType();

            if ($type === null) {
                $this->$setter($value);
                continue;
            }

            $typeName = $type->getName();

            try {
                $convertedValue = $this->convertValue($value, $typeName, $key);
                $this->$setter($convertedValue);
            } catch (Exception $e) {
                throw new InvalidArgumentException("Cannot populate $key: " . $e->getMessage());
            }
        }

        return $this;
    }

    private function convertValue(mixed $value, string $typeName, string $fieldName): mixed
    {
        return match (true) {
            // DateTime
            $typeName === DateTime::class || str_ends_with($typeName, '\\DateTime') =>
                is_string($value) ? new DateTime($value) : $value,

            $typeName === DateTimeImmutable::class || str_ends_with($typeName, '\\DateTimeImmutable') =>
                is_string($value) ? new DateTimeImmutable($value) : $value,

            // Enum
            enum_exists($typeName) && is_string($value) || is_int($value) =>
                $typeName::tryFrom($value) ?? throw new InvalidArgumentException("Invalid enum value for $fieldName"),

            // Integer
            $typeName === 'int' && is_numeric($value) =>
                (int) $value,

            // Boolean
            $typeName === 'bool' =>
                filter_var($value, FILTER_VALIDATE_BOOLEAN),

            // Float
            $typeName === 'float' && is_numeric($value) =>
                (float) $value,

            default => $value,
        };
    }
}

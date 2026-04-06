<?php

namespace App\Database\Model\Repository\Invoice;

use App\Database\Model\Invoice\Connection;

class InvoiceNumberGenerator
{
    public function __construct(private Connection $connection) {}

    public function generate(): string
    {
        $year = (int) date('Y');

        return $this->connection->transactional(function () use ($year) {
            $row = $this->connection->fetchAssociative(
                'SELECT last_number FROM invoice_number_sequence WHERE year = ? FOR UPDATE',
                [$year]
            );

            if (!$row) {
                $this->connection->insert('invoice_number_sequence', [
                    'year' => $year,
                    'last_number' => 1,
                ]);

                $number = 1;
            } else {
                $number = $row['last_number'] + 1;

                $this->connection->update(
                    'invoice_number_sequence',
                    ['last_number' => $number],
                    ['year' => $year]
                );
            }

            return sprintf('%d%06d', $year, $number);
        });
    }
}

<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoice_number_sequence')]
class InvoiceNumberSequence extends BaseEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $year;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $number;

    // Getters

    public function getYear(): int
    {
        return $this->year;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    // Setters

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }
}

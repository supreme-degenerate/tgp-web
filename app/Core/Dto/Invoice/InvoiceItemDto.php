<?php

namespace App\Core\Dto\Invoice;

use App\Core\Base\Database\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceItemDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    public float $pricePerUnit;

    #[Assert\NotBlank]
    #[Assert\Length(max: 3)]
    public string $currency;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    public int $quantity;
}

<?php

namespace App\Core\Dto\Invoice;

use App\Core\Base\Database\BaseDto;
use App\Core\Dto\Address\AddressDto;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Date]
    public string $dueDate;

    #[Assert\Valid]
    public AddressDto $shippingAddress;

    #[Assert\Valid]
    public AddressDto $billingAddress;

    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: 'Invoice must have at least one item')]
    /** @var list<InvoiceItemDto> $items */
    public array $items;
}

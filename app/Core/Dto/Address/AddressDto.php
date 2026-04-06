<?php

namespace App\Core\Dto\Address;

use App\Core\Base\Database\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class AddressDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $addressLine1;

    #[Assert\Length(max: 255)]
    public string $addressLine2;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $city;

    #[Assert\Length(max: 255)]
    public string $state;

    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    public string $postalCode;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $country;
}

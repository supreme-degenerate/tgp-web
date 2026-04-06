<?php

namespace App\Core\Response\Address;

use App\Core\Base\Response\BaseResponse;
use App\Database\Entity\Address\Address;

final class AddressResponse extends BaseResponse
{
    public int $id;
    public string $addressLine1;
    public ?string $addressLine2;
    public string $city;
    public ?string $state;
    public string $postalCode;
    public string $country;

    public function __construct(Address $address)
    {
        $this->id = $address->getId();
        $this->addressLine1 = $address->getAddressLine1();
        $this->addressLine2 = $address->getAddressLine2();
        $this->city = $address->getCity();
        $this->state = $address->getState();
        $this->postalCode = $address->getPostalCode();
        $this->country = $address->getCountry();
    }
}

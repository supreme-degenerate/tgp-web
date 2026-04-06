<?php

namespace App\Database\Entity\Address;

use App\Core\Base\Database\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'addresses')]
class Address extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(name: 'address_line_1', type: 'string', length: 255)]
    protected string $addressLine1;

    #[ORM\Column(name: 'address_line_2', type: 'string', length: 255, nullable: true)]
    protected ?string $addressLine2;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $city;

    #[ORM\Column(type: 'string', length: 255)]
    protected ?string $state;

    #[ORM\Column(type: 'string', length: 20)]
    protected string $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $country;

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    // Setters

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}

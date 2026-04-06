<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'invoice_items')]
class InvoiceItem extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['invoice:read'])]
    protected string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    protected float $pricePerUnit;

    #[ORM\Column(type: 'string', length: 3)]
    protected string $currency;

    #[ORM\Column(type: 'integer')]
    protected int $quantity;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    protected float $totalPrice;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'invoice_id', referencedColumnName: 'id')]
    protected Invoice $invoice;

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPricePerUnit(): float
    {
        return $this->pricePerUnit;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    // Setters

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPricePerUnit(float $pricePerUnit): self
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }
}

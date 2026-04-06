<?php

namespace App\Database\Entity\Invoice;

use App\Core\Base\Database\BaseEntity;
use App\Core\Enum\InvoiceStatus;
use App\Database\Entity\Address\Address;
use App\Database\Repository\Invoice\InvoiceRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: 'invoices')]
#[ORM\HasLifecycleCallbacks]
class Invoice extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 10)]
    protected string $invoiceNumber;

    #[ORM\Column(type: 'integer', enumType: InvoiceStatus::class)]
    protected InvoiceStatus $status;

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'shipping_address_id', referencedColumnName: 'id')]
    protected Address $shippingAddress;

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'billing_address_id', referencedColumnName: 'id')]
    protected Address $billingAddress;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $dueDate;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $raisedAt;

    #[ORM\Column(type: 'integer')]
    protected int $raisedBy;

    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['invoice:read'])]
    protected Collection $items;

    #[ORM\OneToMany(targetEntity: InvoiceAuditLog::class, mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['changedAt' => 'desc'])]
    #[Groups(['invoice:read'])]
    protected Collection $logs;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber ?? '';
    }

    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    public function getStatusName(): string
    {
        return $this->status->getLabel();
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getRaisedAt(): DateTime
    {
        return $this->raisedAt;
    }

    public function getRaisedBy(): int
    {
        return $this->raisedBy;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getLogs(): Collection
    {
        return $this->logs;
    }

    // Setters

    public function setInvoiceNumber(string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function setStatus(InvoiceStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setShippingAddress(Address $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function setBillingAddress(Address $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function setDueDate(DateTime $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function setRaisedAt(DateTime $raisedAt): self
    {
        $this->raisedAt = $raisedAt;

        return $this;
    }

    public function setRaisedBy(int $raisedBy): self
    {
        $this->raisedBy = $raisedBy;

        return $this;
    }

    public function addItem(InvoiceItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);

            $item->setInvoice($this);
        }

        return $this;
    }

    public function removeItem(InvoiceItem $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function addLog(InvoiceAuditLog $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);

            $log->setInvoice($this);
        }

        return $this;
    }
}

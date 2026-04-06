<?php

namespace App\Core\Base\Database;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

abstract class BaseAuditLog extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $field;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $oldValue;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $newValue;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $changedAt;

    #[ORM\Column(type: 'integer')]
    protected int $changedBy;

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOldValue(): string
    {
        return $this->oldValue;
    }

    public function getNewValue(): string
    {
        return $this->newValue;
    }

    public function getChangedAt(): DateTime
    {
        return $this->changedAt;
    }

    public function getChangedBy(): int
    {
        return $this->changedBy;
    }

    // Setters

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setOldValue(string $oldValue): self
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    public function setNewValue(string $newValue): self
    {
        $this->newValue = $newValue;

        return $this;
    }

    public function setChangedAt(DateTime $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function setChangedBy(int $changedBy): self
    {
        $this->changedBy = $changedBy;

        return $this;
    }
}

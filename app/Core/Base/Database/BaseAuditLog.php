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
    protected DateTime $changedBy;
}

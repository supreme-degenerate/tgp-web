<?php

namespace App\Core\Response\Invoice;

use App\Core\Base\Response\BaseResponse;
use App\Database\Entity\Invoice\InvoiceAuditLog;

final class InvoiceAuditLogResponse extends BaseResponse
{
    public int $id;
    public string $field;
    public mixed $oldValue;
    public mixed $newValue;
    public string $changedAt;
    public string $changedBy;
    public string $message;

    public function __construct(InvoiceAuditLog $log)
    {
        $this->id = $log->getId();
        $this->field = $log->getField();
        $this->oldValue = unserialize($log->getOldValue());
        $this->newValue = unserialize($log->getNewValue());
        $this->changedAt = $log->getChangedAt()->format('Y-m-d H:i:s');
        $this->changedBy = $log->getChangedBy();

        $this->message = sprintf('%s has changed from %s to %s', $this->field, $this->convertMixedValueToString($this->oldValue), $this->convertMixedValueToString($this->newValue))
            . ' at ' . $this->changedAt . ' by ' . $this->changedBy . '.';
    }
}

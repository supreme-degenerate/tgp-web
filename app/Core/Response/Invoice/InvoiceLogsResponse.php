<?php

namespace App\Core\Response\Invoice;

use App\Core\Base\Response\BaseResponse;
use App\Database\Entity\Invoice\Invoice;

final class InvoiceLogsResponse extends BaseResponse
{
    public int $id;
    public array $logs = [];

    public function __construct(Invoice $invoice)
    {
        $this->id = $invoice->getId();

        foreach ($invoice->getLogs() as $log) {
            $this->logs[] = new InvoiceAuditLogResponse($log);
        }
    }
}

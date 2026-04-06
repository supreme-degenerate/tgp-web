<?php

namespace App\Presentation\Api\V1\Invoice;

use App\Core\Base\Presentation\BaseApiEntityPresenter;
use App\Database\Entity\Invoice\Invoice;

/**
 * API Invoice Presenter.
 */
class InvoicePresenter extends BaseApiEntityPresenter
{
    /**
     * @inheritDoc
     */
    protected function getEntity(): string
    {
        return Invoice::class;
    }

    /**
     * @inheritDoc
     */
    public function actionSpecial(): void
    {
        $invoiceRepository = $this->entityManager->getRepository(Invoice::class);

        $invoice = $invoiceRepository->find($this->id);


        dump($invoice);
        dumpe('xd');

        $invoice = $this->invoiceRepository->find($id);

        if (!$invoice) {
            $this->sendJson(['error' => 'Invoice not found'], 404);
            return;
        }

        $this->sendJson([
            'id' => $invoice->getId(),
            'number' => $invoice->getNumber(),
            'total' => $invoice->getTotal(),
        ]);
    }
}

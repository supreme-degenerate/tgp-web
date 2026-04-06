<?php

namespace Tests\Unit;

use App\Core\Enum\InvoiceStatus;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class InvoiceStatusChangeTest extends TestCase
{
    public function getInvoiceStatusChangeDataSet(): array
    {
        return [
            'createdToCreated' => [InvoiceStatus::CREATED, InvoiceStatus::CREATED, false],
            'createdToPaid' => [InvoiceStatus::CREATED, InvoiceStatus::PAID, true],
            'createdToCancelled' => [InvoiceStatus::CREATED, InvoiceStatus::CANCELLED, true],
            'createdToRefunded' => [InvoiceStatus::CREATED, InvoiceStatus::REFUNDED, false],
            'paidToCreated' => [InvoiceStatus::PAID, InvoiceStatus::CREATED, false],
            'paidToPaid' => [InvoiceStatus::PAID, InvoiceStatus::PAID, false],
            'paidToCancelled' => [InvoiceStatus::PAID, InvoiceStatus::CANCELLED, false],
            'paidToRefunded' => [InvoiceStatus::PAID, InvoiceStatus::REFUNDED, true],
            'cancelledToCreated' => [InvoiceStatus::CANCELLED, InvoiceStatus::CREATED, false],
            'cancelledToPaid' => [InvoiceStatus::CANCELLED, InvoiceStatus::PAID, false],
            'cancelledToCancelled' => [InvoiceStatus::CANCELLED, InvoiceStatus::CANCELLED, false],
            'cancelledToRefunded' => [InvoiceStatus::CANCELLED, InvoiceStatus::REFUNDED, false],
            'refundedToCreated' => [InvoiceStatus::REFUNDED, InvoiceStatus::CREATED, false],
            'refundedToPaid' => [InvoiceStatus::REFUNDED, InvoiceStatus::PAID, false],
            'refundedToCancelled' => [InvoiceStatus::REFUNDED, InvoiceStatus::CANCELLED, false],
            'refundedToRefunded' => [InvoiceStatus::REFUNDED, InvoiceStatus::REFUNDED, false],
        ];
    }

    /**
     * @dataProvider getInvoiceStatusChangeDataSet
     */
    public function testInvoiceStatusChange(InvoiceStatus $oldStatus, InvoiceStatus $newStatus, bool $result): void
    {
        Assert::same($result, $oldStatus->canChangeTo($newStatus));
    }
}

$test = new InvoiceStatusChangeTest();
$test->run();

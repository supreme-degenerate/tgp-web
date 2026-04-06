<?php

namespace Tests\Unit;

use App\Database\Entity\Invoice\InvoiceNumberSequence;
use App\Database\Repository\Invoice\InvoiceNumberGenerator;
use App\Database\Repository\Invoice\InvoiceNumberSequenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class InvoiceNumberGeneratorTest extends TestCase
{
    public function getInvoiceNumberGeneratorDataSet(): array
    {
        $year = (int) date('Y');

        $sequenceFirstCurrentYear = new InvoiceNumberSequence();
        $sequenceFirstCurrentYear->setYear($year);
        $sequenceFirstCurrentYear->setNumber(1);

        $sequenceSecondCurrentYear = new InvoiceNumberSequence();
        $sequenceSecondCurrentYear->setYear($year);
        $sequenceSecondCurrentYear->setNumber(2);

        return [
            'null' => [null, $year . '000001'],
            'firstCurrentYear' => [$sequenceFirstCurrentYear, $year . '000002'],
            'secondCurrentYear' => [$sequenceSecondCurrentYear, $year . '000003'],
        ];
    }

    /**
     * @dataProvider getInvoiceNumberGeneratorDataSet
     */
    public function testInvoiceNumberGenerator(?InvoiceNumberSequence $sequence, string $resultInvoiceNumber): void
    {
        $repo = Mockery::mock(InvoiceNumberSequenceRepository::class);
        $repo->shouldReceive('findOneByYear')
            ->andReturn($sequence);

        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('getRepository')
            ->andReturn($repo);
        $em->shouldReceive('persist');
        $em->shouldReceive('flush');

        $generator = new InvoiceNumberGenerator($em);

        Assert::equal($resultInvoiceNumber, $generator->generate());
    }
}

$test = new InvoiceNumberGeneratorTest();
$test->run();

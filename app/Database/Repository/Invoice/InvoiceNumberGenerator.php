<?php

namespace App\Database\Repository\Invoice;

use App\Database\Entity\Invoice\InvoiceNumberSequence;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceNumberGenerator
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function generate(): string
    {
        $year = (int) date('Y');

        /** @var InvoiceNumberSequenceRepository $repository */
        $repository = $this->em->getRepository(InvoiceNumberSequence::class);

        $sequence = $repository->findOneByYear($year);

        if ($sequence) {
            $sequence->setNumber($sequence->getNumber() + 1);
        } else {
            $sequence = (new InvoiceNumberSequence())
                ->setYear($year)
                ->setNumber(1);

            $this->em->persist($sequence);
        }

        $this->em->flush();

        return sprintf('%d%06d', $year, $sequence->getNumber());
    }
}

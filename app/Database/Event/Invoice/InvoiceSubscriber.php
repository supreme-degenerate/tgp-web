<?php

namespace App\Database\Event\Invoice;

use App\Core\Enum\InvoiceStatus;
use App\Core\Enum\InvoiceStatusLogicException;
use App\Database\Entity\Invoice\Invoice;
use App\Database\Entity\Invoice\InvoiceAuditLog;
use App\Database\Repository\Invoice\InvoiceNumberGenerator;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

class InvoiceSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::onFlush, Events::postFlush];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Invoice) {
            return;
        }

        if (empty($entity->getInvoiceNumber())) {
            $entity->setInvoiceNumber($this->invoiceNumberGenerator->generate());
        }
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Invoice) {
                continue;
            }

            $changes = $uow->getEntityChangeSet($entity);

            // Validate Invoice Status
            if (isset($changes['status'])) {
                [$oldStatus, $newStatus] = $changes['status'];

                $oldStatus = InvoiceStatus::tryFrom($oldStatus);
                $newStatus = InvoiceStatus::tryFrom($newStatus);

                if (!$oldStatus->canChangeTo($newStatus)) {
                    throw new InvoiceStatusLogicException(sprintf('Invalid status change from %s to %s', $oldStatus->getLabel(), $newStatus->getLabel()));
                }
            }

            // Create Invoice Audit Log
            foreach ($changes as $field => [$old, $new]) {
                $log = (new InvoiceAuditLog())
                    ->setInvoice($entity)
                    ->setField($field)
                    ->setOldValue(serialize($old))
                    ->setNewValue(serialize($new))
                    ->setChangedAt(new DateTime())
                    ->setChangedBy(1);

                $em->persist($log);
                $meta = $em->getClassMetadata(InvoiceAuditLog::class);
                $uow->computeChangeSet($meta, $log);
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        // TODO create new message for RabbitMQ add/update/remove a new invoice document for Elasticsearch
        // ...
    }
}

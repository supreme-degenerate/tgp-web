<?php

namespace App\Database\Event;

use App\Database\Entity\Invoice\Invoice;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class InvoiceAuditSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::onFlush];
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

            foreach ($changes as $field => [$old, $new]) {
                $log = new InvoiceAuditLog(
                    $entity->getId(),
                    $field,
                    $old,
                    $new,
                    new \DateTime()
                );

                $em->persist($log);
            }
        }
    }
}

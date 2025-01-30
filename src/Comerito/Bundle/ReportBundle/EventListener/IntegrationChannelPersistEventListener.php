<?php

namespace Comerito\Bundle\ReportBundle\EventListener;

use Comerito\Bundle\ReportBundle\Integration\ReportChannel;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Oro\Bundle\IntegrationBundle\Entity\Channel;

class IntegrationChannelPersistEventListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Channel) {
            return;
        }

        if ($entity->getType() !== ReportChannel::TYPE) {
            return;
        }

        $entity->setEnabled(false);
    }
}

<?php

namespace Comerito\Bundle\ReportBundle\EventListener;

use Comerito\Bundle\ReportBundle\Integration\ReportChannel;
use Comerito\Bundle\ReportBundle\Loader\ReportsDataLoader;
use Oro\Bundle\IntegrationBundle\Event\Action\ChannelDeleteEvent;
use Oro\Bundle\IntegrationBundle\Event\Action\ChannelDisableEvent;
use Oro\Bundle\IntegrationBundle\Event\Action\ChannelEnableEvent;

readonly class ReportIntegrationListener
{
    public function __construct(
        private ReportsDataLoader $reportsDataLoader,
    )
    {
    }

    // TODO dodać obsługę dodania Integracji

    public function onIntegrationEnable(ChannelEnableEvent $event): void
    {
        $channel = $event->getChannel();
        $channelType = $channel->getType();
        if ($channelType === ReportChannel::TYPE) {
            $transport = $channel->getTransport();

            // TODO add null handling
            $businessUnitName = $transport->getSettingsBag()->get('business_unit_name');

            $this->reportsDataLoader->load($channel, $businessUnitName);
        }
    }

    public function onIntegrationDisableOrDelete(ChannelDisableEvent|ChannelDeleteEvent $event): void
    {
        $channel = $event->getChannel();
        $channelType = $channel->getType();
        if ($channelType === ReportChannel::TYPE) {
            $this->reportsDataLoader->handleDelete();
        }
    }
}

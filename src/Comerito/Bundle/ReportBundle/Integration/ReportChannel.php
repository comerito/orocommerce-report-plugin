<?php

namespace Comerito\Bundle\ReportBundle\Integration;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class ReportChannel implements ChannelInterface, IconAwareIntegrationInterface
{

    const string TYPE = 'comerito_report_channel';

    public function getLabel(): string
    {
        return 'comerito.report_channel.label';
    }

    public function getIcon(): string
    {
        return 'bundles/comeritoreport/img/report_icon.png';
    }
}

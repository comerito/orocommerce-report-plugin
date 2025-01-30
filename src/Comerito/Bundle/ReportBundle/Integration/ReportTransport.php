<?php

namespace Comerito\Bundle\ReportBundle\Integration;

use Comerito\Bundle\ReportBundle\Entity\ReportSettings;
use Comerito\Bundle\ReportBundle\Form\Type\ReportSettingType;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class ReportTransport implements TransportInterface
{
    protected ParameterBag $settings;

    #[\Override]
    public function init(Transport $transportEntity): void
    {
        $this->settings = $transportEntity->getSettingsBag();
    }

    #[\Override]
    public function getSettingsFormType(): string
    {
        return ReportSettingType::class;
    }

    #[\Override]
    public function getSettingsEntityFQCN(): string
    {
        return ReportSettings::class;
    }

    #[\Override]
    public function getLabel(): string
    {
        return 'comerito.report_transport.label';
    }
}

<?php

namespace Comerito\Bundle\ReportBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Entity that represents Report Settings
 */
#[ORM\Entity]
class ReportSettings extends Transport
{
    private ?ParameterBag $settings = null;

    #[ORM\Column(name: 'business_unit_name', type: Types::STRING, length: 255)]
    private ?string $businessUnitName = null;


    public function getBusinessUnitName(): string
    {
        return $this->businessUnitName;
    }

    public function setBusinessUnitName(string $businessUnitName): void
    {
        $this->businessUnitName = $businessUnitName;
    }

    #[\Override]
    public function getSettingsBag(): ParameterBag
    {
        if (null === $this->settings) {
            $this->settings = new ParameterBag([
                'business_unit_name' => $this->getBusinessUnitName(),
            ]);
        }

        return $this->settings;
    }
}

<?php

namespace Comerito\Bundle\ReportBundle\Form\Type;

use Comerito\Bundle\ReportBundle\Entity\ReportSettings;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportSettingType extends AbstractType
{
    public function __construct(
        protected ManagerRegistry $registry
    ) {
    }

    public const BLOCK_PREFIX = 'comerito_report_settings';

    /**
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'businessUnitName',
            ChoiceType::class,
            [
                'label' => 'comerito.report_transport.business_unit_name.label',
                'required' => true,
                'choices' => $this->getBusinessUnitNames(),
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReportSettings::class
        ]);
    }

    protected function getBusinessUnitNames(): array
    {
        // TODO manage access rules to retrieve restricted BusinessUnit list
        $repository = $this->registry->getRepository(BusinessUnit::class);
        $businessUnits = $repository->findAll();

        $result = [];
        foreach ($businessUnits as $businessUnit) {
            $result[$businessUnit->getName()] = $businessUnit->getName();
        }

        return $result;
    }

    public function getBlockPrefix(): string
    {
        return self::BLOCK_PREFIX;
    }
}

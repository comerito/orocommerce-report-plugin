<?php

namespace Comerito\Bundle\ReportBundle\Loader;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\ReportBundle\Entity\Report;
use Oro\Bundle\ReportBundle\Entity\ReportType;

class ReportsDataLoader
{
    public function __construct(
        protected ManagerRegistry $registry,
    )
    {
    }

    // @codingStandardsIgnoreStart
    private array $reports = [
        [
            'name' => 'Total Sales',
            'description' => 'Revenues in a given period of time, broken down by categories, customers, or regions.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Name","func":"","sorting":"DESC"},{"name":"totalValue","label":"Total","func":{"name":"Sum","group_type":"aggregates","group_name":"number"},"sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":true,"useDateGroupFilter":true}}'
        ],
        [
            'name' => 'Average Order Value',
            'description' => 'Average amount of money spent by a customer per transaction. It helps businesses understand customer purchasing behavior and assess the effectiveness of upselling or bundling strategies.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"totalValue","label":"Total value","func":{"name":"Avg","group_type":"aggregates","group_name":"number"},"sorting":"DESC"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Customer name","func":"","sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":false,"useDateGroupFilter":true}}'
        ],
        [
            'name' => 'Reorder Rate',
            'description' => 'Reorder Rate is the percentage of customers who place more than one order within a specific time period, indicating customer loyalty and repeat business.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"id","label":"Number of orders","func":{"name":"Count","group_type":"aggregates","group_name":"number","return_type":"integer"},"sorting":"DESC"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Customer Name","func":"","sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":true,"useDateGroupFilter":true}}'
        ],
        [
            'name' => 'Customer Lifetime Value',
            'description' => 'Customer Lifetime Value (CLV) is the total revenue generated from a customer over the entire duration of their relationship.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Account::class,
            'definition' => '{"columns":[{"name":"name","label":"Customer name","func":"","sorting":""},{"name":"lifetimeValue","label":"Lifetime sales value","func":"","sorting":""}]}'
        ],
        [
            'name' => 'Segment Revenue Share',
            'description' => 'Segment Revenue Share measures the percentage of total revenue contributed by a specific customer segment within a given period.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Account::class,
            'definition' => '{"columns":[{"name":"name","label":"Customer name","func":"","sorting":""},{"name":"lifetimeValue","label":"Lifetime sales value","func":"","sorting":""}]}'
        ],
    ];

    // @codingStandardsIgnoreEnd

    public function load(Channel $channel, string $businessUnitName): void
    {
        $manager = $this->registry->getManager();

        $reportTypeRepository = $manager->getRepository(ReportType::class);
        $businessUnitRepository = $manager->getRepository(BusinessUnit::class); // TODO generically take businessUnit

        foreach ($this->reports as $values) {
            $report = new Report();
            $report->setName('Comerito ' . $values['name']);
            $report->setDescription($values['description']);
            $report->setEntity($values['entity']);
            $report->setType($reportTypeRepository->findOneBy(['name' => $values['type']]));
            $report->setOwner($businessUnitRepository->findOneBy(['name' => $businessUnitName]));
            $report->setDefinition($values['definition']);
            $report->setOrganization($channel->getOrganization());
            $manager->persist($report);
        }

        $manager->flush();
    }

    public function handleDelete(): void
    {
        $manager = $this->registry->getManager();
        $reports = $manager->getRepository(Report::class)->createQueryBuilder('r')
            ->where('r.name like :searchTerm')
            ->setParameter('searchTerm', '%Comerito%')
            ->getQuery()
            ->getResult();

        foreach ($reports as $report) {
            $manager->remove($report);
        }
    }
}

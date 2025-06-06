<?php

namespace Comerito\Bundle\ReportBundle\Loader;

use Comerito\Bundle\ReportBundle\Entity\ReportSettings;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\CustomerBundle\Entity\Customer;
use Oro\Bundle\CustomerBundle\Entity\CustomerGroup;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\ReportBundle\Entity\Report;
use Oro\Bundle\ReportBundle\Entity\ReportType;
use Psr\Log\LoggerInterface;

/** Class responsible to load all report related data to database */
class ReportsDataLoader
{
    private LoggerInterface $logger;

    public function __construct(
        protected ManagerRegistry $registry,
    ) {
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    // @codingStandardsIgnoreStart
    private array $reports = [
        [
            'name' => 'Total Sales',
            'description' => 'Revenues in a given period of time, broken down by categories, customers, or regions.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Name","func":"","sorting":"DESC"},{"name":"totalValue","label":"Total","func":{"name":"Sum","group_type":"aggregates","group_name":"number"},"sorting":""},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::group+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\CustomerGroup::name","label":"Customer Group","func":"","sorting":""},{"name":"billingAddress+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\OrderAddress::country_name","label":"Billing Country","func":"","sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::group+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\CustomerGroup::name"},{"name":"billingAddress+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\OrderAddress::country_name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":true,"useDateGroupFilter":true}}'
        ],
        [
            'name' => 'Average Order Value',
            'description' => 'Average amount of money spent by a customer per transaction. It helps businesses understand customer purchasing behavior and assess the effectiveness of upselling or bundling strategies.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"totalValue","label":"Avg Order Value","func":{"name":"Avg","group_type":"aggregates","group_name":"number"},"sorting":"DESC"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Customer name","func":"","sorting":""},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::group+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\CustomerGroup::name","label":"Customer Group","func":"","sorting":""},{"name":"billingAddress+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\OrderAddress::country_name","label":"Country name","func":"","sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::group+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\CustomerGroup::name"},{"name":"billingAddress+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\OrderAddress::country_name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":true,"useDateGroupFilter":true}}'
        ],
        [
            'name' => 'Returning Customers',
            'description' => 'Returning Customers report presents customers who place more than one order within a specific time period, indicating customer loyalty and repeat business.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Order::class,
            'definition' => '{"columns":[{"name":"id","label":"Number of orders","func":{"name":"Count","group_type":"aggregates","group_name":"number","return_type":"integer"},"sorting":"DESC"},{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name","label":"Customer Name","func":"","sorting":""}],"grouping_columns":[{"name":"customer+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::name"}],"date_grouping":{"fieldName":"createdAt","useSkipEmptyPeriodsFilter":true,"useDateGroupFilter":true},"filters":[[{"columnName":"id","criterion":{"filter":"number","data":{"value":1,"type":"2","params":{"filter_by_having":true}}},"func":{"name":"Count","group_type":"aggregates","group_name":"number","return_type":"integer"},"criteria":"aggregated-condition-item"}]],"expression":""}'
        ],
        [
            'name' => 'Customer Lifetime Value',
            'description' => 'Customer Lifetime Value (CLV) is the total revenue generated from a customer over the entire duration of their relationship.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Account::class,
            'definition' => '{"columns":[{"name":"name","label":"Customer name","func":"","sorting":""},{"name":"lifetimeValue","label":"Lifetime sales value","func":"","sorting":"DESC"}]}'
        ],
        [
            'name' => 'Segment Revenue Share',
            'description' => 'Segment Revenue Share measures the total revenue contributed by a specific customer segment within a given period.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => CustomerGroup::class,
            'definition' => '{"columns":[{"name":"name","label":"Name","func":"","sorting":""},{"name":"Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::group+Oro\\\\Bundle\\\\CustomerBundle\\\\Entity\\\\Customer::Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::customer+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::totalValue","label":"Total","func":{"name":"Sum","group_type":"aggregates","group_name":"number"},"sorting":"DESC"}],"grouping_columns":[{"name":"name"}]}'
        ],
        [
            'name' => 'Total Customer Spend',
            'description' => 'This report tracks the total amount spent by each customer, helping you identify top buyers and analyze their contribution to your revenue.',
            'type' => ReportType::TYPE_TABLE,
            'entity' => Customer::class,
            'definition' => '{"columns":[{"name":"name","label":"Customer","func":"","sorting":""},{"name":"Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::customer+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::totalValue","label":"Total Revenue","func":{"name":"Sum","group_type":"aggregates","group_name":"number"},"sorting":"DESC"}],"grouping_columns":[{"name":"name"}],"filters":[[{"columnName":"Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::customer+Oro\\\\Bundle\\\\OrderBundle\\\\Entity\\\\Order::totalValue","criterion":{"filter":"number","data":{"value":0,"type":"2","params":{"filter_by_having":true}}},"func":{"name":"Sum","group_type":"aggregates","group_name":"number"},"criteria":"aggregated-condition-item"}]]}'
        ]
    ];

    // @codingStandardsIgnoreEnd

    public function load(Channel $channel, string $businessUnitName): void
    {
        $manager = $this->registry->getManager();

        $reportTypeRepository = $manager->getRepository(ReportType::class);
        $businessUnitRepository = $manager->getRepository(BusinessUnit::class);

        $organization = $channel->getOrganization();

        if (!($organization instanceof Organization)) {
            $this->logger->error(
                'ComeritoReportPlugin.ReportsDataLoader: Invalid organization type unable to load reports.'
            );
            return;
        }

        // Get the transport to store report IDs
        $transport = $channel->getTransport();
        if (!($transport instanceof ReportSettings)) {
            $this->logger->error(
                'ComeritoReportPlugin.ReportsDataLoader: Invalid transport type unable to store report IDs.'
            );
            return;
        }

        $createdReportIds = [];
        
        foreach ($this->reports as $values) {
            $report = new Report();
            $report->setName($values['name']);
            $report->setDescription($values['description']);
            $report->setEntity($values['entity']);
            $report->setType($reportTypeRepository->findOneBy(['name' => $values['type']]));
            $report->setOwner($businessUnitRepository->findOneBy(['name' => $businessUnitName]));
            $report->setDefinition($values['definition']);
            $report->setOrganization($organization);
            $manager->persist($report);
            $manager->flush(); 

            $createdReportIds[] = $report->getId();
        }
   
        $transport->setReportIds($createdReportIds);
        $manager->persist($transport);
        $manager->flush();
    }

    public function handleDelete(Channel $channel): void
    {
        $manager = $this->registry->getManager();
        $transport = $channel->getTransport();
        
        if (!($transport instanceof ReportSettings)) {
            return;
        }
        
        $reportIds = $transport->getReportIds();
        
        if (empty($reportIds)) {
            return;
        }
        
        $reports = $manager->getRepository(Report::class)->findBy(['id' => $reportIds]);
        
        foreach ($reports as $report) {
            $manager->remove($report);
        }
        

        $transport->setReportIds(null);
        $manager->persist($transport);
        $manager->flush();
    }
}

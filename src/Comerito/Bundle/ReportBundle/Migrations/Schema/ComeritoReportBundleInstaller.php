<?php

namespace Comerito\Bundle\ReportBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class ComeritoReportBundleInstaller implements Installation
{

    public function getMigrationVersion(): string
    {
        return 'v0_5';
    }

    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('business_unit_name', Types::STRING, ['length' => 255, 'notnull' => false]);
    }
}

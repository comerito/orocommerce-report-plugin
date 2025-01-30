<?php

namespace Comerito\Bundle\ReportBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AddFieldsToOroTransportTable implements Migration
{

    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('comerito_report_business_unit', Types::STRING, ['length' => 255, 'notnull' => false]);
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version1 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql($this->connection->getDriver()->getDatabasePlatform()->getDummySelectSQL());
    }
}

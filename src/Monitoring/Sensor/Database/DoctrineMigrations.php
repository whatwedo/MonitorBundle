<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Sensor\Database;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Version\MigrationStatusCalculator;
use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class DoctrineMigrations extends AbstractSensor
{
    public function __construct(
        protected ?DependencyFactory $dependencyFactory = null
    ) {
    }

    public function getName(): string
    {
        return 'Doctrine Migrations';
    }

    public function isEnabled(): bool
    {
        return $this->dependencyFactory instanceof DependencyFactory;
    }

    public function run(): void
    {
        $this->state = SensorStateEnum::SUCCESSFUL;

        /** @var MigrationStatusCalculator $migrationStatus */
        $migrationStatus = $this->dependencyFactory->getMigrationStatusCalculator();

        $value = count($migrationStatus->getExecutedUnavailableMigrations());
        if ($value > 0) {
            $this->details['executed_unavailable_migrations'] = $value;
            $this->state = SensorStateEnum::WARNING;
        }

        $value = count($migrationStatus->getNewMigrations());
        if ($value > 0) {
            $this->details['new_migrations'] = $value;
            $this->state = SensorStateEnum::CRITICAL;
        }
    }
}

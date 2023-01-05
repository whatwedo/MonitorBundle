<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Sensor\Database;

use Doctrine\Migrations\Version\MigrationStatusCalculator;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class DoctrineMigrations extends AbstractSensor implements ServiceSubscriberInterface
{
    public function __construct(
        protected ContainerInterface $container
    ) {
    }

    public function getName(): string
    {
        return 'Doctrine Migrations';
    }

    public function isEnabled(): bool
    {
        return $this->container->has(MigrationStatusCalculator::class);
    }

    public function run(): void
    {
        $this->state = SensorStateEnum::SUCCESSFUL;

        /** @var MigrationStatusCalculator $migrationStatus */
        $migrationStatus = $this->container->get(MigrationStatusCalculator::class);

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

    public static function getSubscribedServices(): array
    {
        return [
            '?' . MigrationStatusCalculator::class,
        ];
    }
}

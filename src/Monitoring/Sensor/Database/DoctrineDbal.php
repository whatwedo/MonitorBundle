<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Sensor\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class DoctrineDbal extends AbstractSensor implements ServiceSubscriberInterface
{
    public function __construct(
        protected ContainerInterface $container
    ) {
    }

    public function getName(): string
    {
        return 'Doctrine DBAL';
    }

    public function isEnabled(): bool
    {
        return $this->container->has(ManagerRegistry::class);
    }

    public function run(): void
    {
        $this->state = SensorStateEnum::SUCCESSFUL;

        /**
         * @var string $name
         * @var Connection $connection
         */
        foreach ($this->container->get(ManagerRegistry::class)->getConnections() as $name => $connection) {
            try {
                $connection->executeQuery(
                    $connection->getDriver()->getDatabasePlatform()->getDummySelectSQL()
                );
            } catch (Exception $e) {
                $this->details['exception'] = $e->getMessage();
                $this->state = SensorStateEnum::CRITICAL;
            }
        }
    }

    public static function getSubscribedServices(): array
    {
        return [
            '?'.ManagerRegistry::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Database;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use whatwedo\MonitorBundle\Manager\MonitoringManager;
use whatwedo\MonitorBundle\Monitoring\Sensor\Database\DoctrineMigrations;
use whatwedo\MonitorBundle\Tests\Monitoring\AbstractMonitoringTest;
use whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Database\Migrations\Version1;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class DoctrineMigrationsTest extends AbstractMonitoringTest
{
    use UseTestKernelTrait;

    public function testSuccessful(): void
    {
        $kernel = self::bootKernel([
            'config' => [$this, 'configureSuccessfulKernel'],
        ]);
        $application = new Application($kernel);
        $monitoringManager = $kernel->getContainer()->get(MonitoringManager::class);

        // execute first migration
        $command = $application->find('doctrine:migrations:execute');
        $args = new ArrayInput([
            'versions' => [Version1::class],
        ]);
        $args->setInteractive(false);
        $command->run($args, new NullOutput());

        // only one migration executed
        self::assertFalse($monitoringManager->isSuccessful(), 'Only one migration executed');

        // execute all migrations
        $command = $application->find('doctrine:migrations:migrate');
        $args = new ArrayInput([]);
        $args->setInteractive(false);
        $command->run($args, new NullOutput());

        // all migrations executed
        $monitoringManager->run();
        self::assertTrue($monitoringManager->isSuccessful(), 'All migrations executed');
    }

    public static function configureSuccessfulKernel(TestKernel $kernel): void
    {
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(DoctrineMigrationsBundle::class);
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_dbal_successful.yml');
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_migrations.yml');
    }

    public static function configureFailureKernel(TestKernel $kernel): void
    {
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(DoctrineMigrationsBundle::class);
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_dbal_successful.yml');
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_migrations.yml');
    }

    protected function getMonitoringClass(): string
    {
        return DoctrineMigrations::class;
    }
}

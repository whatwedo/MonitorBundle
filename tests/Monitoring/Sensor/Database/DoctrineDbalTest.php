<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Database;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nyholm\BundleTest\TestKernel;
use whatwedo\MonitorBundle\Monitoring\Sensor\Database\DoctrineDbal;
use whatwedo\MonitorBundle\Tests\Monitoring\AbstractMonitoringTest;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class DoctrineDbalTest extends AbstractMonitoringTest
{
    use UseTestKernelTrait;

    public static function configureSuccessfulKernel(TestKernel $kernel): void
    {
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_dbal_successful.yml');
    }

    public static function configureFailureKernel(TestKernel $kernel): void
    {
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestConfig(__DIR__ . '/config/doctrine_dbal_error.yml');
    }

    protected function getMonitoringClass(): string
    {
        return DoctrineDbal::class;
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use whatwedo\MonitorBundle\Manager\MonitoringManager;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

abstract class AbstractMonitoringTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public static function configureSuccessfulKernel(TestKernel $kernel): void
    {
    }

    public static function configureFailureKernel(TestKernel $kernel): void
    {
    }

    public function testDisabled(): void
    {
        self::assertFalse($this->getAttribute(self::bootKernel())->isEnabled());
    }

    public function testEnabled(): void
    {
        self::assertTrue($this->getAttribute(self::bootKernel([
            'config' => [$this, 'configureSuccessfulKernel'],
        ]))->isEnabled());
    }

    public function testSuccessful(): void
    {
        self::assertTrue(self::bootKernel([
            'config' => [$this, 'configureSuccessfulKernel'],
        ])->getContainer()->get(MonitoringManager::class)->isSuccessful());
    }

    public function testFailure(): void
    {
        self::assertFalse(self::bootKernel([
            'config' => [$this, 'configureFailureKernel'],
        ])->getContainer()->get(MonitoringManager::class)->isSuccessful());
    }

    abstract protected function getMonitoringClass(): string;

    protected function getAttribute(TestKernel $kernel): AttributeInterface
    {
        return $kernel->getContainer()
            ->get(MonitoringManager::class)
            ->getAttribute($this->getMonitoringClass());
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use whatwedo\MonitorBundle\Manager\MonitoringManager;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

abstract class AbstractMonitoring extends KernelTestCase
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
        $kernel = self::bootKernel([
            'config' => [$this, 'configureSuccessfulKernel'],
        ]);
        self::assertTrue($kernel->getContainer()->get(MonitoringManager::class)->isSuccessful());
    }

    public function testName(): void
    {
        $kernel = self::bootKernel([
            'config' => [$this, 'configureSuccessfulKernel'],
        ]);

        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        self::assertStringContainsString($this->getAttribute($kernel)->getName(), $commandTester->getDisplay());
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

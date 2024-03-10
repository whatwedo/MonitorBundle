<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Service;

use Nyholm\BundleTest\TestKernel;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\MercureBundle\MercureBundle;
use Symfony\Component\Mercure\Exception\RuntimeException;
use Symfony\Component\Mercure\HubInterface;
use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Tests\Monitoring\AbstractMonitoring;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class Mercure extends AbstractMonitoring
{
    use UseTestKernelTrait;

    public function testSuccessful(): void
    {
        $hub = $this->getHub();
        $hub->method('publish')->willReturn('');

        $attribute = new self($hub);
        $attribute->run();
        self::assertEquals(SensorStateEnum::SUCCESSFUL, $attribute->getState());
    }

    public function testFailure(): void
    {
        $hub = $this->getHub();
        $hub->method('publish')->willThrowException(new RuntimeException());

        $attribute = new self($hub);
        $attribute->run();
        self::assertEquals(SensorStateEnum::CRITICAL, $attribute->getState());
    }

    public static function configureSuccessfulKernel(TestKernel $kernel): void
    {
        $kernel->addTestBundle(MercureBundle::class);
        $kernel->addTestConfig(__DIR__.'/config/mercure.yml');
    }

    protected function getHub(): MockObject&HubInterface
    {
        $hub = $this->createMock(HubInterface::class);
        $hub->method('getUrl')->willReturn('http://127.0.0.1');
        $hub->method('getPublicUrl')->willReturn('https://example.com');

        return $hub;
    }

    protected function getMonitoringClass(): string
    {
        return self::class;
    }
}

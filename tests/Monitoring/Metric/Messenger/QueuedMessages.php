<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Metric\Messenger;

use Symfony\Component\DependencyInjection\ServiceLocator;
use whatwedo\MonitorBundle\Enums\MetricStateEnum;
use whatwedo\MonitorBundle\Tests\Monitoring\AbstractMonitoring;
use whatwedo\MonitorBundle\Tests\Monitoring\Metric\Messenger\Transport\MockTransport;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class QueuedMessages extends AbstractMonitoring
{
    use UseTestKernelTrait;

    public function testEnabled(): void
    {
        self::assertTrue($this->createMockAttribute(0)->isEnabled());
    }

    public function testSuccessful(): void
    {
        $attribute = $this->createMockAttribute(1);
        $attribute->run();
        self::assertEquals(MetricStateEnum::OK, $attribute->getState());
    }

    public function testWarning(): void
    {
        $attribute = $this->createMockAttribute(5);
        $attribute->run();
        self::assertEquals(MetricStateEnum::WARNING, $attribute->getState());
    }

    public function testFailure(): void
    {
        $attribute = $this->createMockAttribute(50);
        $attribute->run();
        self::assertEquals(MetricStateEnum::CRITICAL, $attribute->getState());
    }

    public function testName(): void
    {
        self::assertStringContainsString('Queued Messages', $this->createMockAttribute(0)->getName());
    }

    protected function createMockAttribute(int $messageCount): self
    {
        $serviceLocator = $this->createMock(ServiceLocator::class);
        $serviceLocator->method('getProvidedServices')->willReturn([
            'default' => '?',
        ]);
        $serviceLocator->method('get')->willReturn(new MockTransport($messageCount));

        return new self(5, 10, $serviceLocator);
    }

    protected function getMonitoringClass(): string
    {
        return self::class;
    }
}

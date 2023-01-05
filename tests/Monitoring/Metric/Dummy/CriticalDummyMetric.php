<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy;

use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;

class CriticalDummyMetric extends AbstractMetric
{
    public function getName(): string
    {
        return 'Critical Test';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        $this->state = MetricStateEnum::CRITICAL;
        $this->value = 24;
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy;

use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;

class WarningDummyMetric extends AbstractMetric
{
    public function getName(): string
    {
        return 'Warning Test';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        $this->state = MetricStateEnum::WARNING;
        $this->value = 125;
    }
}

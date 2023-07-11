<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy;

use whatwedo\MonitorBundle\Enums\MetricStateEnum;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;

class OkDummyMetric extends AbstractMetric
{
    public function getName(): string
    {
        return 'Ok Test';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        $this->state = MetricStateEnum::OK;
        $this->value = 80;
    }
}

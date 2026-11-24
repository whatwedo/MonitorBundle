<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Metric;

use whatwedo\MonitorBundle\Enums\MetricStateEnum;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;

abstract class AbstractMetric implements AttributeInterface, MetricStateInterface
{
    public int|float|null $value = null;

    public ?MetricStateEnum $state = null;

    public function getState(): MetricStateEnum
    {
        if ($this->state === null) {
            throw new \LogicException(static::class.'::$state is not set.');
        }

        return $this->state;
    }

    public function getValue(): int|float|null
    {
        return $this->value;
    }
}

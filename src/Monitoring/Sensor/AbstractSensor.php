<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Sensor;

use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;

abstract class AbstractSensor implements AttributeInterface, SensorStateInterface
{
    public ?SensorStateEnum $state = null;

    public array $details = [];

    public function getState(): SensorStateEnum
    {
        if ($this->state === null) {
            throw new \LogicException(static::class.'::$state is not set.');
        }

        return $this->state;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}

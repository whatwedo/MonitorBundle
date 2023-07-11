<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy;

use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class WarningDummySensor extends AbstractSensor
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
        $this->state = SensorStateEnum::WARNING;
    }
}

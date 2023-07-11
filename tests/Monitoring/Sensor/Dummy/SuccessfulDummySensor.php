<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy;

use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class SuccessfulDummySensor extends AbstractSensor
{
    public function getName(): string
    {
        return 'Successful Test';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        $this->state = SensorStateEnum::SUCCESSFUL;
    }
}

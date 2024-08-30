<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Sensor;

use whatwedo\MonitorBundle\Enums\SensorStateEnum;

interface SensorStateInterface
{
    /**
     * @throws \LogicException
     */
    public function getState(): SensorStateEnum;
}

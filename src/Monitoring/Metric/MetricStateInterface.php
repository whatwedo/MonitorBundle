<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Metric;

use whatwedo\MonitorBundle\Enums\MetricStateEnum;

interface MetricStateInterface
{
    /**
     * @throws \LogicException
     */
    public function getState(): MetricStateEnum;
}

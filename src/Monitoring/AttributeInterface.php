<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring;

interface AttributeInterface
{
    public function getName(): string;

    public function isEnabled(): bool;

    public function run(): void;
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Enums;

enum SensorStateEnum: string
{
    public function getCliColor(): string
    {
        return match ($this) {
            self::SUCCESSFUL => 'green',
            self::WARNING => 'yellow',
            self::CRITICAL => 'red',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::SUCCESSFUL => '✓',
            self::WARNING => '!',
            self::CRITICAL => '✗',
        };
    }
    case SUCCESSFUL = 'successful';
    case WARNING = 'warning';
    case CRITICAL = 'critical';
}

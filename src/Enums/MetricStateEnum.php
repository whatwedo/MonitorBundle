<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Enums;

enum MetricStateEnum: string
{
    public function getCliColor(): string
    {
        return match ($this) {
            self::OK => 'green',
            self::WARNING => 'yellow',
            self::CRITICAL => 'red',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::OK => '✓',
            self::WARNING => '!',
            self::CRITICAL => '✗',
        };
    }

    case OK = 'ok';
    case WARNING = 'warning';
    case CRITICAL = 'critical';
}

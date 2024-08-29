<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Manager;

use whatwedo\MonitorBundle\Enums\MetricStateEnum;
use whatwedo\MonitorBundle\Enums\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\MetricStateInterface;
use whatwedo\MonitorBundle\Monitoring\Sensor\SensorStateInterface;

class MonitoringManager
{
    /**
     * @var AttributeInterface[]
     */
    protected array $attributes = [];

    protected ?bool $isSuccessful = null;

    protected ?bool $hasWarnings = null;

    public function run(): void
    {
        $this->isSuccessful = true;
        $this->hasWarnings = false;

        foreach ($this->attributes as $attribute) {
            if ($attribute->isEnabled()) {
                $attribute->run();
                $wasSuccessful = $this->wasSuccessful($attribute);
                $wasWarning = $this->wasWarning($attribute);
                if (! $wasSuccessful && ! $wasWarning) {
                    $this->isSuccessful = false;
                }
                if ($wasWarning) {
                    $this->hasWarnings = true;
                }
            }
        }
    }

    public function getResult(): array
    {
        if ($this->isSuccessful === null) {
            $this->run();
        }

        $data = [];

        foreach ($this->attributes as $attribute) {
            if (! $attribute->isEnabled()) {
                continue;
            }

            $groups = explode('\\', get_class($attribute));
            $groups = array_slice($groups, array_search('Monitoring', $groups, true) + 1 ?: 0, -1);
            $groups = array_map(static fn ($e) => strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $e)), $groups);
            $attributeKey = array_pop($groups);

            $tmp = &$data;
            foreach ($groups as $i => $group) {
                for ($s = 0; $s < $i; $s++) {
                    $tmp = &$data[$groups[$s]];
                }

                if (! isset($tmp[$group])) {
                    $tmp[$group] = [];
                }
                $tmp = &$tmp[$group];
            }

            $tmp[$attributeKey][] = $attribute;
        }

        return $data;
    }

    public function isSuccessful(): bool
    {
        if ($this->isSuccessful === null) {
            $this->run();
        }

        return $this->isSuccessful;
    }

    public function isWarning(): bool
    {
        if ($this->hasWarnings === null) {
            $this->run();
        }

        return $this->hasWarnings;
    }

    public function addAttribute(AttributeInterface $attribute): void
    {
        $this->attributes[$attribute::class] = $attribute;
    }

    public function getAttribute(string $className): AttributeInterface
    {
        return $this->attributes[$className];
    }

    private function wasSuccessful(AttributeInterface $attribute): bool
    {
        if ($attribute instanceof SensorStateInterface) {
            return $attribute->getState() === SensorStateEnum::SUCCESSFUL;
        }

        if ($attribute instanceof MetricStateInterface) {
            return $attribute->getState() === MetricStateEnum::OK;
        }

        return false;
    }

    private function wasWarning(AttributeInterface $attribute): bool
    {
        if ($attribute instanceof SensorStateInterface) {
            return $attribute->getState() === SensorStateEnum::WARNING;
        }

        if ($attribute instanceof MetricStateInterface) {
            return $attribute->getState() === MetricStateEnum::WARNING;
        }

        return false;
    }
}

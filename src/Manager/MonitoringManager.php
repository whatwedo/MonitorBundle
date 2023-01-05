<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Manager;

use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class MonitoringManager
{
    /**
     * @var AttributeInterface[]
     */
    protected array $attributes = [];

    protected ?bool $isSuccessful = null;

    public function run(): void
    {
        $this->isSuccessful = true;

        foreach ($this->attributes as $attribute) {
            if ($attribute->isEnabled()) {
                $attribute->run();
                if (($attribute instanceof AbstractSensor
                        && $attribute->getState() !== SensorStateEnum::SUCCESSFUL)
                    || ($attribute instanceof AbstractMetric
                        && $attribute->getState() !== MetricStateEnum::OK)) {
                    $this->isSuccessful = false;
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

    public function addAttribute(AttributeInterface $attribute): void
    {
        $this->attributes[$attribute::class] = $attribute;
    }

    public function getAttribute(string $className): AttributeInterface
    {
        return $this->attributes[$className];
    }
}

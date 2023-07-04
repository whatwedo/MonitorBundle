<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;

class EnumNormalizer implements NormalizerInterface
{
    /**
     * @param MetricStateEnum|SensorStateEnum $object
     */
    public function normalize($object, string $format = null, array $context = []): string
    {
        return $object->value;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof MetricStateEnum || $data instanceof SensorStateEnum;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MetricStateEnum::class => true,
            SensorStateEnum::class => true,
        ];
    }
}

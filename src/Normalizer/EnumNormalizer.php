<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;

/**
 * symfony 5.3 compatibility (https://github.com/symfony/symfony/pull/40830 has been merged into 5.4)
 */
class EnumNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
        return ! class_exists('Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer')
            && ($data instanceof MetricStateEnum || $data instanceof SensorStateEnum);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}

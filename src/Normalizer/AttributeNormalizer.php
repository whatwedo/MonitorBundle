<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;
use whatwedo\MonitorBundle\Monitoring\Metric\MetricStateInterface;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;
use whatwedo\MonitorBundle\Monitoring\Sensor\SensorStateInterface;

class AttributeNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param AttributeInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'name' => $object->getName(),
        ];

        try {
            if ($object instanceof MetricStateInterface || $object instanceof SensorStateInterface) {
                $data['state'] = $this->normalizer->normalize($object->getState());
            }
        } catch (\LogicException) {
        }

        if ($object instanceof AbstractSensor) {
            $data['details'] = $object->getDetails();
        }

        if ($object instanceof AbstractMetric) {
            $data['value'] = $object->getValue();
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AttributeInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            AttributeInterface::class => true,
        ];
    }
}

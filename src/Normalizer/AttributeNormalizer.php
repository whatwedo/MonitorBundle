<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class AttributeNormalizer implements NormalizerInterface
{
    /**
     * @param AttributeInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'name' => $object->getName(),
        ];

        if ($object instanceof AbstractSensor) {
            $data['state'] = $object->getState();
            $data['details'] = $object->getDetails();
        }

        if ($object instanceof AbstractMetric) {
            $data['state'] = $object->getState();
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

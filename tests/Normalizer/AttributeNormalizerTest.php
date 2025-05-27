<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Normalizer;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy\CriticalDummyMetric;
use whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy\LogicErrorDummyMetric;
use whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy\OkDummyMetric;
use whatwedo\MonitorBundle\Tests\Monitoring\Metric\Dummy\WarningDummyMetric;
use whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy\CriticalDummySensor;
use whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy\LogicErrorDummySensor;
use whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy\SuccessfulDummySensor;
use whatwedo\MonitorBundle\Tests\Monitoring\Sensor\Dummy\WarningDummySensor;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

final class AttributeNormalizerTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public static function provideDummyMetricsAndSensors(): array
    {
        return [
            CriticalDummyMetric::class => [
                'attribute' => new CriticalDummyMetric(),
                'expectedNormalizedBody' => [
                    'name' => 'Critical Test',
                    'state' => 'critical',
                    'value' => 24,
                ],
            ],
            OkDummyMetric::class => [
                'attribute' => new OkDummyMetric(),
                'expectedNormalizedBody' => [
                    'name' => 'Ok Test',
                    'state' => 'ok',
                    'value' => 80,
                ],
            ],
            WarningDummyMetric::class => [
                'attribute' => new WarningDummyMetric(),
                'expectedNormalizedBody' => [
                    'name' => 'Warning Test',
                    'state' => 'warning',
                    'value' => 125,
                ],
            ],

            CriticalDummySensor::class => [
                'attribute' => new CriticalDummySensor(),
                'expectedNormalizedBody' => [
                    'name' => 'Critical Test',
                    'state' => 'critical',
                    'details' => [],
                ],
            ],
            SuccessfulDummySensor::class => [
                'attribute' => new SuccessfulDummySensor(),
                'expectedNormalizedBody' => [
                    'name' => 'Successful Test',
                    'state' => 'successful',
                    'details' => [],
                ],
            ],
            WarningDummySensor::class => [
                'attribute' => new WarningDummySensor(),
                'expectedNormalizedBody' => [
                    'name' => 'Warning Test',
                    'state' => 'warning',
                    'details' => [],
                ],
            ],

            LogicErrorDummyMetric::class => [
                'attribute' => new LogicErrorDummyMetric(),
                'expectedNormalizedBody' => [
                    'name' => 'Logic Error Test',
                    'value' => null,
                ],
            ],
            LogicErrorDummySensor::class => [
                'attribute' => new LogicErrorDummySensor(),
                'expectedNormalizedBody' => [
                    'name' => 'Logic Error Test',
                    'details' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideDummyMetricsAndSensors
     */
    public function testNormalizeWorksAsExpected(AttributeInterface $attribute, array $expectedNormalizedBody): void
    {
        $attribute->run();

        self::assertSame(
            $expectedNormalizedBody,
            $this->getContainer()->get(NormalizerInterface::class)->normalize($attribute)
        );
    }
}

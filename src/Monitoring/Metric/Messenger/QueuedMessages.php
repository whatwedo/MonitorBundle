<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Monitoring\Metric\Messenger;

use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use whatwedo\MonitorBundle\Enum\MetricStateEnum;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;

class QueuedMessages extends AbstractMetric
{
    public function __construct(
        protected int $warningThreshold,
        protected int $criticalThreshold,
        protected ?ServiceLocator $receiverLocator = null,
        protected ?TransportInterface $failureTransport = null,
    ) {
    }

    public function getName(): string
    {
        return 'Queued Messages';
    }

    public function isEnabled(): bool
    {
        return $this->receiverLocator instanceof ServiceLocator;
    }

    public function run(): void
    {
        $this->value = 0;
        $uniqueTransports = [];

        foreach (array_keys($this->receiverLocator->getProvidedServices()) as $transportName) {
            $transport = $this->receiverLocator->get($transportName);
            if ($transport === $this->failureTransport) {
                continue;
            }
            if (in_array($transport, $uniqueTransports, true)) {
                continue;
            }
            $uniqueTransports[] = $transport;

            if ($transport instanceof MessageCountAwareInterface) {
                $this->value += $transport->getMessageCount();
            }
        }

        match (true) {
            $this->value >= $this->criticalThreshold => $this->state = MetricStateEnum::CRITICAL,
            $this->value >= $this->warningThreshold => $this->state = MetricStateEnum::WARNING,
            default => $this->state = MetricStateEnum::OK,
        };
    }
}

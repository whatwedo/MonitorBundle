<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Monitoring\Metric\Messenger\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class MockTransport implements TransportInterface, MessageCountAwareInterface
{
    public function __construct(
        protected int $messageCount
    ) {
    }

    public function getMessageCount(): int
    {
        return $this->messageCount;
    }

    public function get(): iterable
    {
        return [];
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function reject(Envelope $envelope): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        return new Envelope(new \stdClass());
    }
}

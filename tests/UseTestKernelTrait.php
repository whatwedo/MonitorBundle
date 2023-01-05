<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests;

use Nyholm\BundleTest\TestKernel;
use Symfony\Component\HttpKernel\KernelInterface;
use whatwedo\MonitorBundle\whatwedoMonitorBundle;

trait UseTestKernelTrait
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        /** @var TestKernel $kernel */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(whatwedoMonitorBundle::class);
        $kernel->addTestConfig(__DIR__ . '/config/framework.yml');
        $kernel->handleOptions($options);

        return $kernel;
    }
}

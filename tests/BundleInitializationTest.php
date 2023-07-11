<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use whatwedo\MonitorBundle\Command\CheckCommand;
use whatwedo\MonitorBundle\Controller\ApiController;
use whatwedo\MonitorBundle\Controller\DashboardController;
use whatwedo\MonitorBundle\Manager\MonitoringManager;

class BundleInitializationTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public function testManagerExisting(): void
    {
        self::assertInstanceOf(
            MonitoringManager::class,
            self::getContainer()->get(MonitoringManager::class)
        );
    }

    public function testDisabledApi(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/config/disabled_api.yml');
            },
        ]);

        self::assertFalse($kernel->getContainer()->has(ApiController::class));
    }

    public function testDisabledController(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/config/disabled_controller.yml');
            },
        ]);

        self::assertFalse($kernel->getContainer()->has(DashboardController::class));
    }

    public function testDisabledCommand(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/config/disabled_command.yml');
            },
        ]);

        self::assertFalse($kernel->getContainer()->has(CheckCommand::class));
    }
}

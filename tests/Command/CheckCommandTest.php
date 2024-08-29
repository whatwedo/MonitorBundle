<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Command;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class CheckCommandTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public function testSuccessful(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_successful.yml');
            },
        ]);

        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(0, $commandTester->getStatusCode());
    }

    public function testWarning(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_warning.yml');
            },
        ]);

        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(1, $commandTester->getStatusCode());
    }

    public function testWarningCustomExitCode(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_warning_custom_exit_code.yml');
            },
        ]);

        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(2, $commandTester->getStatusCode());
    }

    public function testCritical(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_critical.yml');
            },
        ]);
        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(1, $commandTester->getStatusCode());
    }

    public function testCriticalCustomExitCode(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_critical_custom_exit_code.yml');
            },
        ]);
        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(-1, $commandTester->getStatusCode());
    }

    public function testLogicError(): void
    {
        $kernel = self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_logic_error.yml');
            },
        ]);
        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $logicException = false;
        try {
            $commandTester->execute([]);
        } catch (\LogicException $e) {
            $logicException = true;
        } finally {
            self::assertTrue($logicException);
        }
    }
}

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
                $kernel->addTestConfig(__DIR__ . '/../config/dummy_successful.yml');
            },
        ]);

        $application = new Application($kernel);
        $command = $application->find('whatwedo:monitor:check');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        self::assertEquals(0, $commandTester->getStatusCode());
    }
}

<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Controller;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class DashboardControllerTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public function testDashboard(): void
    {
        $client = $this->getClient($this->getApiKernel());
        $client->request('GET', '/dashboard');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Ok Test', $client->getResponse()->getContent());
        $this->assertStringContainsString('Critical Test', $client->getResponse()->getContent());
        $this->assertStringContainsString('Successful Test', $client->getResponse()->getContent());
        $this->assertStringContainsString('Warning Test', $client->getResponse()->getContent());
        $this->assertStringContainsString('✗', $client->getResponse()->getContent());
        $this->assertStringContainsString('✓', $client->getResponse()->getContent());
        $this->assertStringContainsString('!', $client->getResponse()->getContent());
        $this->assertStringContainsString('24', $client->getResponse()->getContent());
        $this->assertStringContainsString('80', $client->getResponse()->getContent());
        $this->assertStringContainsString('125', $client->getResponse()->getContent());
    }

    protected function getApiKernel(): KernelInterface
    {
        return self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestRoutingFile(__DIR__ . '/config/routes.yml');
                $kernel->addTestConfig(__DIR__ . '/../config/dummy.yml');
                $kernel->addTestBundle(TwigBundle::class);
            },
        ]);
    }

    protected function getClient(KernelInterface $kernel): AbstractBrowser
    {
        return $kernel->getContainer()
            ->get('test.client');
    }
}

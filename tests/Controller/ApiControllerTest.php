<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Tests\Controller;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use whatwedo\MonitorBundle\Tests\UseTestKernelTrait;

class ApiControllerTest extends KernelTestCase
{
    use UseTestKernelTrait;

    public function testApiJson(): void
    {
        $client = $this->getClient($this->getApiKernel());
        $client->request('GET', '/api.json');
        $this->assertEquals(503, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/responses/result.json', $client->getResponse()->getContent());
    }

    public function testApiJsonPretty(): void
    {
        $client = $this->getClient($this->getApiKernel());
        $client->request('GET', '/api.json?pretty');
        $this->assertEquals(503, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/responses/result_pretty.json', $client->getResponse()->getContent());
    }

    public function testApiXml(): void
    {
        $client = $this->getClient($this->getApiKernel());
        $client->request('GET', '/api.xml');
        $this->assertEquals(503, $client->getResponse()->getStatusCode());
        $this->assertXmlStringEqualsXmlFile(__DIR__.'/responses/result.xml', $client->getResponse()->getContent());
    }

    public function testApiProtectedSuccessful(): void
    {
        $client = $this->getClient($this->getProtectedApiKernel());
        $client->request('GET', '/api.json', server: [
            'HTTP_X-Auth-Token' => 'secret',
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testApiProtectedNoAuth(): void
    {
        $client = $this->getClient($this->getProtectedApiKernel());
        $client->request('GET', '/api.json');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testApiProtectedAuthFailure(): void
    {
        $client = $this->getClient($this->getProtectedApiKernel());
        $client->request('GET', '/api.json', server: [
            'HTTP_X-Auth-Token' => 'invalid',
        ]);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testApiCriticalCustomHttpStatusCode(): void
    {
        $client = $this->getClient($this->getApiKernelCriticalCustomHttpStatusCode());
        $client->request('GET', '/api.json');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    protected function getApiKernel(): KernelInterface
    {
        return self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy.yml');
                $kernel->addTestRoutingFile(__DIR__.'/config/routes.yml');
            },
        ]);
    }

    protected function getApiKernelCriticalCustomHttpStatusCode(): KernelInterface
    {
        return self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestConfig(__DIR__.'/../config/dummy_critical_custom_http_status_code.yml');
                $kernel->addTestRoutingFile(__DIR__.'/config/routes.yml');
            },
        ]);
    }

    protected function getProtectedApiKernel(): KernelInterface
    {
        return self::bootKernel([
            'config' => static function (TestKernel $kernel) {
                $kernel->addTestRoutingFile(__DIR__.'/config/routes.yml');
                $kernel->addTestConfig(__DIR__.'/config/protected_api.yml');
            },
        ]);
    }

    protected function getClient(KernelInterface $kernel): AbstractBrowser
    {
        return $kernel->getContainer()
            ->get('test.client');
    }
}

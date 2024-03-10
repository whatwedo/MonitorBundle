<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use whatwedo\MonitorBundle\Command\CheckCommand;
use whatwedo\MonitorBundle\Controller\ApiController;
use whatwedo\MonitorBundle\Controller\DashboardController;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\Messenger\QueuedMessages;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class whatwedoMonitorExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        // tag monitoring attributes (sensors and metrics)
        $container->registerForAutoconfiguration(AttributeInterface::class)
            ->addTag('whatwedo_monitor.attribute');

        $this->configureEndpoints($container, $config);
        $this->configureMonitoring($container, $config);
    }

    public function configureEndpoints(ContainerBuilder $container, array $config)
    {
        // register API
        $container->findDefinition(ApiController::class)
            ->addArgument($config['endpoint']['api']['http_status_code']['warning'] ?? 503)
            ->addArgument($config['endpoint']['api']['http_status_code']['critical'] ?? 503)
            ->addArgument($config['endpoint']['api']['auth_token'] ?? null)
        ;

        if (isset($config['endpoint']['api']['enabled'])
            && ! $config['endpoint']['api']['enabled']) {
            $container->removeDefinition(ApiController::class);
        }

        // register controller
        if (isset($config['endpoint']['controller']['enabled'])
            && ! $config['endpoint']['controller']['enabled']) {
            $container->removeDefinition(DashboardController::class);
        }

        // register command
        if (isset($config['endpoint']['command']['enabled'])
            && ! $config['endpoint']['command']['enabled']) {
            $container->removeDefinition(CheckCommand::class);
        }
    }

    public function configureMonitoring(ContainerBuilder $container, array $config)
    {
        $container->findDefinition(QueuedMessages::class)
            ->setArgument(0, $config['monitoring']['metric']['messenger']['queued_messages']['warning_threshold'] ?? 5)
            ->setArgument(1, $config['monitoring']['metric']['messenger']['queued_messages']['critical_threshold'] ?? 10);
        if (! (isset($config['endpoint']['command']['enabled'])
            && ! $config['endpoint']['command']['enabled'])) {
            $container->findDefinition(CheckCommand::class)
                ->setArgument(0, $config['endpoint']['command']['exit_code']['warning'] ?? 1)
                ->setArgument(1, $config['endpoint']['command']['exit_code']['critical'] ?? 1)
            ;
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
    }
}

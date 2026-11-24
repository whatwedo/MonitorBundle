<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use whatwedo\MonitorBundle\Command\CheckCommand;
use whatwedo\MonitorBundle\Controller\ApiController;
use whatwedo\MonitorBundle\Controller\DashboardController;
use whatwedo\MonitorBundle\DependencyInjection\CompilerPass\AttributeCompilerPass;
use whatwedo\MonitorBundle\Monitoring\AttributeInterface;
use whatwedo\MonitorBundle\Monitoring\Metric\Messenger\QueuedMessages;

class whatwedoMonitorBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AttributeCompilerPass());
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('endpoint')
                    ->children()
                        ->arrayNode('api')
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultTrue()
                                    ->info('Enable the api endpoint (default true)')
                                ->end()
                                ->scalarNode('auth_token')
                                    ->defaultNull()
                                    ->info('Auth token for the api endpoint, must be sent with the X-Auth-Token HTTP header (default null / disabled)')
                                ->end()
                                ->arrayNode('http_status_code')
                                    ->children()
                                        ->integerNode('warning')
                                            ->defaultValue(503)
                                            ->info('HTTP status code for warning state (default 503)')
                                        ->end()
                                        ->integerNode('critical')
                                            ->defaultValue(503)
                                            ->info('HTTP status code for critical state (default 503)')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('controller')
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultTrue()
                                    ->info('Enable the controller endpoint (default true)')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('command')
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultTrue()
                                    ->info('Enable the command endpoint (default true)')
                                ->end()
                                ->arrayNode('exit_code')
                                    ->children()
                                        ->integerNode('warning')
                                            ->defaultValue(1)
                                            ->info('Exit code for warning state (default 1)')
                                        ->end()
                                        ->integerNode('critical')
                                            ->defaultValue(1)
                                            ->info('Exit code for critical state (default 1)')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('monitoring')
                    ->children()
                        ->arrayNode('metric')
                            ->children()
                                ->arrayNode('messenger')
                                    ->children()
                                        ->arrayNode('queued_messages')
                                            ->info('Checks the number of messages in the messenger queue')
                                            ->children()
                                                ->integerNode('warning_threshold')
                                                    ->info('Number of messages in the queue before the metric is in warning state (default 5)')
                                                ->end()
                                                ->integerNode('critical_threshold')
                                                    ->info('Number of messages in the queue before the metric is in critical state (default 10)')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $configurator->import('../config/services.php');

        $container->registerForAutoconfiguration(AttributeInterface::class)
            ->addTag('whatwedo_monitor.attribute');

        $this->configureEndpoints($container, $config);
        $this->configureMonitoring($container, $config);
    }

    private function configureEndpoints(ContainerBuilder $container, array $config): void
    {
        $container->findDefinition(ApiController::class)
            ->addArgument($config['endpoint']['api']['http_status_code']['warning'] ?? 503)
            ->addArgument($config['endpoint']['api']['http_status_code']['critical'] ?? 503)
            ->addArgument($config['endpoint']['api']['auth_token'] ?? null)
        ;

        if (! ($config['endpoint']['api']['enabled'] ?? true)) {
            $container->removeDefinition(ApiController::class);
        }

        if (! ($config['endpoint']['controller']['enabled'] ?? true)) {
            $container->removeDefinition(DashboardController::class);
        }

        if (! ($config['endpoint']['command']['enabled'] ?? true)) {
            $container->removeDefinition(CheckCommand::class);
        }
    }

    private function configureMonitoring(ContainerBuilder $container, array $config): void
    {
        $container->findDefinition(QueuedMessages::class)
            ->setArgument(0, $config['monitoring']['metric']['messenger']['queued_messages']['warning_threshold'] ?? 5)
            ->setArgument(1, $config['monitoring']['metric']['messenger']['queued_messages']['critical_threshold'] ?? 10);

        if ($container->hasDefinition(CheckCommand::class)) {
            $container->findDefinition(CheckCommand::class)
                ->setArgument(0, $config['endpoint']['command']['exit_code']['warning'] ?? 1)
                ->setArgument(1, $config['endpoint']['command']['exit_code']['critical'] ?? 1)
            ;
        }
    }
}

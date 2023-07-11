<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('whatwedo_monitor');

        $treeBuilder->getRootNode()
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

        return $treeBuilder;
    }
}

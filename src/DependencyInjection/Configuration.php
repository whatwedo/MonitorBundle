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
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

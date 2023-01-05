<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use whatwedo\MonitorBundle\Manager\MonitoringManager;

class AttributeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(MonitoringManager::class);

        foreach ($container->findTaggedServiceIds('whatwedo_monitor.attribute') as $id => $tags) {
            $definition->addMethodCall('addAttribute', [new Reference($id)]);
        }
    }
}

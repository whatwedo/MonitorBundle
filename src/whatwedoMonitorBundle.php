<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use whatwedo\MonitorBundle\DependencyInjection\CompilerPass\AttributeCompilerPass;

class whatwedoMonitorBundle extends Bundle
{
    /**
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AttributeCompilerPass());
    }
}

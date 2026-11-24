<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use whatwedo\MonitorBundle\Monitoring\Metric\Messenger\QueuedMessages;
use whatwedo\MonitorBundle\Monitoring\Sensor\Database\DoctrineMigrations;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->load('whatwedo\\MonitorBundle\\', '../src/*')
        ->exclude([
            '../src/DependencyInjection/',
            '../src/Enums/',
        ]);

    $services->load('whatwedo\\MonitorBundle\\Controller\\', '../src/Controller/')
        ->tag('controller.service_arguments');

    $services->load('whatwedo\\MonitorBundle\\Normalizer\\', '../src/Normalizer/')
        ->tag('serializer.normalizer');

    $services->set(QueuedMessages::class)
        ->arg('$receiverLocator', service('messenger.receiver_locator')->nullOnInvalid())
        ->arg('$failureTransport', service('messenger.failure_transports.default')->nullOnInvalid());

    $services->set(DoctrineMigrations::class)
        ->args([service('doctrine.migrations.dependency_factory')->nullOnInvalid()]);
};

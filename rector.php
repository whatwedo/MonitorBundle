<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// Symfony rules follow the installed version: run against the lowest supported dependencies (composer update --prefer-lowest).
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withComposerBased(symfony: true)
    ->withAttributesSets(symfony: true);

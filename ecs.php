<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\ClassCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FileCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentThrowTagSniff;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $containerConfigurator): void {
    $containerConfigurator->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // run and fix, one by one
    $containerConfigurator->import('vendor/whatwedo/php-coding-standard/config/whatwedo-symfony.php');

    $containerConfigurator->skip([
        NoSuperfluousPhpdocTagsFixer::class,
        FileCommentSniff::class,
        ClassCommentSniff::class,
        FunctionCommentThrowTagSniff::class,
        ValidClassNameSniff::class => [
            __DIR__ . '/src/whatwedoMonitorBundle.php',
            __DIR__ . '/src/DependencyInjection/whatwedoMonitorExtension.php',
        ],
        PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer::class => [
            __DIR__ . '/src/DependencyInjection/Configuration.php',
        ],
        PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer::class => [
            __DIR__ . '/src/Manager/MonitoringManager.php',
        ],
    ]);

    $containerConfigurator->parallel();
};

<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\ClassCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FileCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentThrowTagSniff;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use whatwedo\PhpCodingStandard\Set\WhatwedoSets;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        WhatwedoSets::SYMFONY,
    ])
    ->withSkip([
        NoSuperfluousPhpdocTagsFixer::class,
        FileCommentSniff::class,
        ClassCommentSniff::class,
        FunctionCommentThrowTagSniff::class,
        ValidClassNameSniff::class => [
            __DIR__ . '/src/whatwedoMonitorBundle.php',
            __DIR__ . '/src/DependencyInjection/whatwedoMonitorExtension.php',
        ],
        MethodChainingIndentationFixer::class => [
            __DIR__ . '/src/DependencyInjection/Configuration.php',
        ],
        NoNullableBooleanTypeFixer::class => [
            __DIR__ . '/src/Manager/MonitoringManager.php',
        ],
    ])
    ->withParallel();

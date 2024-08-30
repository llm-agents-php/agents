<?php

declare(strict_types=1);

use WayOfDev\PhpCsFixer\Config\ConfigBuilder;
use WayOfDev\PhpCsFixer\Config\RuleSets\ExtendedPERSet;

require_once 'vendor/autoload.php';

$config = ConfigBuilder::createFromRuleSet(new ExtendedPERSet())
    ->inDir(__DIR__ . '/src')
    ->inDir(__DIR__ . '/tests')
    ->addFiles([
        __FILE__,
        __DIR__ . '/rector.php',
    ])
    ->useParallelConfig()
    ->getConfig()
;

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/php-cs-fixer.cache');

return $config;

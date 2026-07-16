<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in('scripts')
    ->in('src')
    ->in('tests');

return (new Config())
    ->setRules([
        '@PER-CS3.0' => true,
        'fully_qualified_strict_types' => ['import_symbols' => true],
        'ordered_imports' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder($finder);

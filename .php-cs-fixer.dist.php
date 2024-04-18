<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setCacheFile('var/cache/.php-cs-fixer.cache')
    ->setRules([
        '@PER-CS' => true,
        '@Symfony' => true,
        'global_namespace_import' => false,
    ])
    ->setFinder($finder)
;

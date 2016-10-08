<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('stubs');

$rules = [
    'psr0' => false,
    '@PSR2' => true,
    'short_array_syntax' => true,
    'concat_with_spaces' => true,
    'phpdoc_order' => true,
    'ordered_imports' => true,
    'include' => true,
    'object_operator_without_whitespace' => true,
    'binary_operator_spaces' => true,
    'phpdoc_align' => true,
    'blank_line_before_return' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'cast_spaces' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'no_unused_imports' => true,
    'no_whitespace_in_blank_lines' => true,
    'ordered_imports' => false,
];

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->finder($finder)
    ->setUsingCache(true)
    ->setCacheFile($cacheDir . '/.php_cs.cache');

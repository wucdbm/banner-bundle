<?php

$finder = PhpCsFixer\Finder::create()
//    ->exclude('somedir')
//    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__ . '/src');

return PhpCsFixer\Config::create()
    ->registerCustomFixers([
        new \Wucdbm\PhpCsFixer\Fixer\EnsureBlankLineAfterClassOpeningFixer()
    ])
    ->setRules([
        '@Symfony'                                     => true,
        'no_blank_lines_after_class_opening'           => false,
        'Wucdbm/ensure_blank_line_after_class_opening' => true,
        'array_syntax'                                 => [
            'syntax' => 'short'
        ],
        'braces'                                       => [
            'position_after_functions_and_oop_constructs' => 'same'
        ]
    ])
    ->setUsingCache(false)
    ->setFinder($finder);
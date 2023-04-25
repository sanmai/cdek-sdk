<?php

$header = trim('This code is licensed under the MIT License.'.substr(file_get_contents('LICENSE'), strlen('The MIT License')));

use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'explicit_indirect_variable' => true,
        'no_superfluous_elseif' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => false,
        'non_printable_character' => true,
        'ordered_imports' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'visibility_required' => true,
        'header_comment' => ['header' => $header, 'separate' => 'bottom', 'location' => 'after_open', 'comment_type' => 'PHPDoc'],
        'ternary_to_null_coalescing' => true,
        'yoda_style' => false,
        'phpdoc_to_comment' => false,
        'binary_operator_spaces' => ['operators' => ['=>' => BinaryOperatorSpacesFixer::ALIGN]],
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'strict_comparison' => true,
        'native_function_invocation' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->in(__DIR__)
    )
;

return $config;

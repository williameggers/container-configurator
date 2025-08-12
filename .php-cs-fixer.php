<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                                      => true,
        'array_syntax'                                => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align',
                '=' => 'align',
            ],
        ],
        'concat_space'                                => [
            'spacing' => 'one',
        ],
        'declare_strict_types'                        => true,
        'type_declaration_spaces'                     => true,
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],
        'lowercase_cast'                              => true,
        'class_attributes_separation' => [
            'elements' => ['method' => 'one', 'property' => 'one', 'const' => 'one'],
        ],
        'native_function_casing'                      => true,
        'new_with_parentheses'                        => true,
        'no_alias_functions'                          => true,
        'no_blank_lines_after_class_opening'          => true,
        'no_blank_lines_after_phpdoc'                 => true,
        'no_empty_comment'                            => true,
        'no_empty_phpdoc'                             => true,
        'no_empty_statement'                          => true,
        'no_extra_blank_lines'                        => true,
        'no_leading_import_slash'                     => true,
        'no_leading_namespace_whitespace'             => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'multiline_whitespace_before_semicolons'      => false,
        'no_short_bool_cast'                          => true,
        'no_singleline_whitespace_before_semicolons'  => true,
        'no_spaces_around_offset'                     => true,
        'no_trailing_comma_in_singleline'             => true,
        'no_unreachable_default_argument_value'       => true,
        'no_unused_imports'                           => true,
        'no_useless_else'                             => true,
        'no_useless_return'                           => true,
        'no_whitespace_before_comma_in_array'         => true,
        'object_operator_without_whitespace'          => true,
        'ordered_imports'                             => true,
        'phpdoc_align'                                => true,
        'phpdoc_indent'                               => true,
        'general_phpdoc_tag_rename'                   => true,
        'phpdoc_inline_tag_normalizer'                => true,
        'phpdoc_tag_type'                             => true,
        'phpdoc_order'                                => true,
        'phpdoc_scalar'                               => true,
        'phpdoc_separation'                           => true,
        'phpdoc_single_line_var_spacing'              => true,
        'phpdoc_summary'                              => true,
        'phpdoc_to_comment'                           => true,
        'phpdoc_trim'                                 => true,
        'phpdoc_types'                                => true,
        'self_accessor'                               => true,
        'short_scalar_cast'                           => true,
        'single_quote'                                => true,
        'space_after_semicolon'                       => true,
        'standardize_not_equals'                      => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],
        ],
        'trim_array_spaces'                           => true,
        'unary_operator_spaces'                       => true,
        'whitespace_after_comma_in_array'             => true,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setFinder($finder);
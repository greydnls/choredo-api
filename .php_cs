<?php

$finder = PhpCsFixer\Finder::create();

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                                 => true,
        '@DoctrineAnnotation'                   => true,
        '@Symfony'                              => true,
        'align_multiline_comment'               => ['comment_type' => 'all_multiline'],
        'array_syntax'                          => ['syntax' => 'short'],
        'binary_operator_spaces'                => ['default' => 'align'],
        'blank_line_after_opening_tag'          => true,
        'cast_spaces'                           => ['space' => 'single'],
        'compact_nullable_typehint'             => true,
        'concat_space'                          => ['spacing' => 'one'],
        'declare_strict_types'                  => true,
        'dir_constant'                          => true,
        'increment_style'                       => false,
        'linebreak_after_opening_tag'           => true,
        'mb_str_functions'                      => true,
        'no_null_property_initialization'       => true,
        'no_superfluous_elseif'                 => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else'                       => true,
        'no_useless_return'                     => true,
        'ordered_class_elements'                => true,
        'ordered_imports'                       => true,
        'phpdoc_order'                          => true,
        'psr4'                                  => true,
        'yoda_style'                            => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false
        ],

    ])
    ->setFinder($finder);
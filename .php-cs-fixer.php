<?php

$dirs = [
    'src',
    'tests',
];

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,
        'blank_line_after_opening_tag' => true,
        'method_argument_space' => false,
    ])
    ->setFinder((new PhpCsFixer\Finder())
        ->in(
            array_map(
                static fn (string $dir) => __DIR__ . '/' . $dir,
                $dirs,
            )
        ),
    );

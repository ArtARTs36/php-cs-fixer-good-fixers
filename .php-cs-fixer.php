<?php

$finder = PhpCsFixer\Finder::create()->in(['src', 'tests']);

return (new \PhpCsFixer\Config())
    ->registerCustomFixers([
        new \ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfaceMethodPhpDocSummaryFixer(),
        new \ArtARTs36\PhpCsFixerGoodFixers\Fixer\DisableFunctionFixer(),
    ])
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);

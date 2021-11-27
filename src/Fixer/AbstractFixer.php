<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\PhpCsFixerGoodFixers\Token\TokenHelper;
use ArtARTs36\Str\Str;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

abstract class AbstractFixer implements FixerInterface
{
    private $name;

    protected $helper;

    public function __construct()
    {
        $this->name = 'PhpCsFixerGoodFixers/' . Str::make(static::class)
                ->explode('\\')
                ->last()
                ->delete(['Fixer'])
                ->splitByDifferentCases()
                ->implode('_')
                ->toLower();

        $this->helper = new TokenHelper();
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriority(): int
    {
        return 2;
    }

    public function supports(\SplFileInfo $file): bool
    {
        return true;
    }
}

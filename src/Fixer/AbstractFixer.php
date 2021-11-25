<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\Str\Str;
use PhpCsFixer\Fixer\FixerInterface;

abstract class AbstractFixer implements FixerInterface
{
    private $name;

    public function __construct()
    {
        $this->name = 'PhpCsFixerGoodFixers/' . Str::make(static::class)
                ->explode('\\')
                ->last()
                ->delete(['Fixer'])
                ->splitByDifferentCases()
                ->implode('_')
                ->toLower();
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

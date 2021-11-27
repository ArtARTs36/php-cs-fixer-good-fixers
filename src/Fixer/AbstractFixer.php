<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\Str\Str;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Tokenizer\Tokens;

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

    protected function getFirstIndex(Tokens $tokens): int
    {
        foreach ($tokens as $index => $token) {
            return $index;
        }

        return 0;
    }

    protected function getClassName(Tokens $tokens): ?string
    {
        $classTokenIndex = $tokens->getNextTokenOfKind($this->getFirstIndex($tokens), [[T_CLASS]]);

        return $tokens[$tokens->getNextTokenOfKind($classTokenIndex, [[T_STRING]])]->getContent();
    }
}

<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use PhpCsFixer\Fixer\FixerInterface;

abstract class AbstractFixer implements FixerInterface
{
    public function isRisky(): bool
    {
        return false;
    }
}

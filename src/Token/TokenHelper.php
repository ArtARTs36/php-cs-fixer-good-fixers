<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Token;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class TokenHelper
{
    public function getFirstIndex(Tokens $tokens): int
    {
        foreach ($tokens as $index => $token) {
            return $index;
        }

        return 0;
    }

    public function getClassName(Tokens $tokens): ?string
    {
        $classTokenIndex = $tokens->getNextTokenOfKind($this->getFirstIndex($tokens), [[T_CLASS]]);

        return $tokens[$tokens->getNextTokenOfKind($classTokenIndex, [[T_STRING]])]->getContent();
    }

    public function isNull(Token $token): bool
    {
        return $token->getContent() === null;
    }
}

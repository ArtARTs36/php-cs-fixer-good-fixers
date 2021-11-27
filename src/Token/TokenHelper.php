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
        return $token->getContent() === 'null';
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    public function getNextTokenId(
        Tokens $tokens,
        int $fromIndex,
        int $maxLength,
        ?int $searchToken,
        ?string $searchValue = null
    ): ?int {
        for ($i = $fromIndex + 1; $i < ($fromIndex + $maxLength + 1); $i++) {
            if ($tokens[$i]->getId() === $searchToken) {
                if ($searchValue === null) {
                    return $i;
                }

                if ($tokens[$i]->getContent() === $searchValue) {
                    return $i;
                }

                return null;
            }
        }

        return null;
    }

    public function getNextAssignTokenId(Tokens $tokens, int $fromIndex, int $maxLength): ?int
    {
        return $this->getNextTokenId($tokens, $fromIndex, $maxLength, null, '=');
    }

    public function getNextNullOrEmptyStringTokenId(Tokens $tokens, int $fromIndex, int $maxLength)
    {

    }
}

<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Token;

use PhpCsFixer\Tokenizer\Analyzer\Analysis\NamespaceUseAnalysis;
use PhpCsFixer\Tokenizer\Analyzer\NamespaceUsesAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class TokenHelper
{
    protected $nsUsesAnalyzer;

    public function __construct()
    {
        $this->nsUsesAnalyzer = new NamespaceUsesAnalyzer();
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    public function isClassExtends(Tokens $tokens, string $parentClass): bool
    {
        $parentClass = ltrim($parentClass, '\\');

        if (! ($tokens->isTokenKindFound(T_CLASS) && $tokens->isTokenKindFound(T_EXTENDS))) {
            return false;
        }

        $extends = ltrim($this->getClassExtends($tokens), '\\');

        if ($extends === $parentClass) {
            return true;
        }

        $use = $this->getClassUse($tokens, $parentClass);

        return $use !== null && $extends === $use->getShortName();
    }

    public function getClassExtends(Tokens $tokens): string
    {
        return $this->buildExtendsClassLink(
            $tokens,
            $tokens->getNextTokenOfKind($this->getFirstIndex($tokens), [[T_EXTENDS]])
        );
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    protected function buildExtendsClassLink(Tokens $tokens, int $extendsTokenId): string
    {
        $length = $tokens->count();
        $link = '';

        for ($tokenId = $extendsTokenId + 2; $tokenId <= $length; $tokenId++) {
            if ($tokens[$tokenId]->getId() === T_STRING || $tokens[$tokenId]->getId() === T_NS_SEPARATOR) {
                $link .= $tokens[$tokenId]->getContent();
            } else {
                break;
            }
        }

        return $link;
    }

    public function getClassUse(Tokens $tokens, string $useClassOrTrait): ?NamespaceUseAnalysis
    {
        foreach ($this->nsUsesAnalyzer->getDeclarationsFromTokens($tokens) as $declaration) {
            if (ltrim($declaration->getFullName(), '\\') === $useClassOrTrait) {
                return $declaration;
            }
        }

        return null;
    }

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
     * @param array<string>|string $searchValue
     */
    public function getNextTokenId(
        Tokens $tokens,
        int $fromIndex,
        int $maxLength,
        ?int $searchToken,
        $searchValue = null
    ): ?int {
        for ($i = $fromIndex + 1; $i < ($fromIndex + $maxLength + 1); $i++) {
            $valueEquals = in_array($tokens[$i]->getContent(), (array) $searchValue);

            if ($searchToken === null && $valueEquals) {
                return $i;
            }

            if ($tokens[$i]->getId() === $searchToken) {
                if ($searchValue === null) {
                    return $i;
                }

                if ($valueEquals) {
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

    public function createNewLineToken(): Token
    {
        return new Token([T_WHITESPACE, "\n"]);
    }
}

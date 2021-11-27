<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\Str\Str;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class LaravelCommandNoEmptyDescriptionFixer extends AbstractFixer
{
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_CLASS) &&
               $tokens->isTokenKindFound(T_EXTENDS) &&
               $tokens->isTokenKindFound(T_PROTECTED) &&
               $tokens->isTokenKindFound(T_VARIABLE);
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    public function fix(\SplFileInfo $file, Tokens $tokens): void
    {
        $hasProperty = false;
        $descriptionValueTokensIds = [];
        $className = '';

        for ($index = 0; $index < $tokens->count(); $index++) {
            $token = $tokens[$index];

            if ($token->isGivenKind(T_CLASS)) {
                $className = $tokens[$tokens->getNextTokenOfKind($index, [[T_STRING]])]->getContent();
            }

            if ($token->isGivenKind(T_VARIABLE) && $token->getContent() === '$description') {
                $assignTokenId = $tokens->getNextTokenOfKind($index, ['=']);
                $valueTokenId = $assignTokenId + 1;
                $hasProperty = true;

                if ($tokens[$valueTokenId]->isGivenKind(T_WHITESPACE)) {
                    $valueTokenId++;
                }

                $closeTokenId = $tokens->getNextTokenOfKind($valueTokenId, [';']);
                $newDescription = '';

                for ($i = $valueTokenId; $i < $closeTokenId; $i++) {
                    $descriptionValueTokensIds[] = $i;
                    $newDescription .= $tokens[$i]->getContent();
                }

                break;
            }
        }

        $neededDescription = Str::make($className)
            ->deleteWhenEnds('Command')
            ->splitByDifferentCases()
            ->implode(' ')
            ->toLower()
            ->upFirstSymbol();

        if ($hasProperty) {
            if (count($descriptionValueTokensIds) === 1) {
                [$valueTokenId] = $descriptionValueTokensIds;

                if ($tokens[$valueTokenId]->isGivenKind(T_CONSTANT_ENCAPSED_STRING)) {
                    $tokens[$valueTokenId] = new Token([T_CONSTANT_ENCAPSED_STRING, "'$neededDescription'"]);
                }
            }
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        // TODO: Implement getDefinition() method.
    }
}

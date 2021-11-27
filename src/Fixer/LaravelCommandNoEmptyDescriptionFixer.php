<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\Str\Str;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;

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
        $tokensAnalyzer = new TokensAnalyzer($tokens);

        $classElements = $tokensAnalyzer->getClassyElements();

        $neededDescription = $this->generateDescription($this->getClassName($tokens));

        foreach ($classElements as $index => $element) {
            if ($element['type'] === 'property' && $element['token']->getContent() === '$description') {
                if ($tokens[$index + 1]->isGivenKind(T_WHITESPACE) &&
                    $tokens[$index + 2]->getContent() === '='
                ) {
                    if ($tokens[$index + 3]->isGivenKind(T_WHITESPACE) &&
                        ($this->tokenIsNull($tokens[$index + 4]) ||
                        mb_strlen(trim($tokens[$index + 4]->getContent(), "'")) === 0)
                    ) {
                        $tokens[$index + 4] = new Token([T_CONSTANT_ENCAPSED_STRING, "'$neededDescription'"]);
                    }
                }

                if ($tokens[$index + 1]->getContent() === ';') {
                    $tokens->insertAt($index + 1,[
                        new Token([T_WHITESPACE, ' ']),
                        new Token([T_STRING, '=']),
                        new Token([T_WHITESPACE, ' ']),
                        new Token([T_CONSTANT_ENCAPSED_STRING, "'$neededDescription'"]),
                    ]);
                }
            }
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        // TODO: Implement getDefinition() method.
    }

    protected function generateDescription(string $className): string
    {
        return Str::make($className)
            ->deleteWhenEnds('Command')
            ->splitByDifferentCases()
            ->implode(' ')
            ->toLower()
            ->upFirstSymbol();
    }
}

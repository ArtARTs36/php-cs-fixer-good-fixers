<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\PhpCsFixerGoodFixers\Property\PropBuilder;
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

        $neededDescription = $this->generateDescription($this->helper->getClassName($tokens));

        $signatureLineLastTokenIndex = null;

        foreach ($classElements as $index => $element) {
            if ($element['type'] === 'property' && $element['token']->getContent() === '$description') {
                if (($assignTokenIndex = $this->helper->getNextAssignTokenId($tokens, $index, 2))) {
                    $valueTokenIndex = $this->helper->getNextTokenId($tokens, $assignTokenIndex, 3, null, [
                        'null',
                        "''",
                    ]);

                    if ($valueTokenIndex !== null) {
                        $tokens[$valueTokenIndex] = new Token([T_CONSTANT_ENCAPSED_STRING, "'$neededDescription'"]);

                        return;
                    }
                }

                if ($tokens[$index + 1]->getContent() === ';') {
                    $tokens->insertAt($index + 1, [
                        new Token([T_WHITESPACE, ' ']),
                        new Token([T_STRING, '=']),
                        new Token([T_WHITESPACE, ' ']),
                        new Token([T_CONSTANT_ENCAPSED_STRING, "'$neededDescription'"]),
                    ]);

                    return;
                }
            }

            if ($element['type'] === 'property' && $element['token']->getContent() === '$signature') {
                $signatureLineLastTokenIndex = $tokens->getNextTokenOfKind($index, [';']);
            }
        }

        $insertIndex = $signatureLineLastTokenIndex;

        if ($insertIndex === null) {
            $classTokenIndex = $tokens->getNextTokenOfKind($this->helper->getFirstIndex($tokens), [[T_CLASS]]);
            $insertIndex = $tokens->getNextTokenOfKind($classTokenIndex, ['{']) + 1;
        }

        $propBuilder = new PropBuilder('description');

        $tokens->insertAt(
            $insertIndex,
            array_merge(
                [new Token([T_WHITESPACE, "\n"])],
                $propBuilder->setProtected()->setValueString($neededDescription)->buildLine()
            ),
        );
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

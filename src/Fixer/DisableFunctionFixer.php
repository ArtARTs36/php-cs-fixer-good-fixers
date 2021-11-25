<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Analyzer\ArgumentsAnalyzer;
use PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class DisableFunctionFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('', [], '');
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return true;
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    public function fix(\SplFileInfo $file, Tokens $tokens): void
    {
        $functions = array_flip(['dd', 'dump']);
        $funcAnalyzer = new FunctionsAnalyzer();
        $argsAnalyzer = new ArgumentsAnalyzer();

        $forRemoveIndexes = [];

        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            if (! $tokens[$index]->isGivenKind(T_STRING) ||
                ! isset($functions[$tokens[$index]->getContent()]) ||
                ! $funcAnalyzer->isGlobalFunctionCall($tokens, $index)
            ) {
                continue;
            }

            $openParenthesis = $tokens->getNextMeaningfulToken($index);
            $closeParenthesis = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openParenthesis);
            $arguments = $argsAnalyzer->getArguments($tokens, $openParenthesis, $closeParenthesis);

            $forRemoveIndexes = array_merge(
                $forRemoveIndexes,
                [$index, $openParenthesis, $closeParenthesis, $tokens->getNextTokenOfKind($index, [';'])],
                $arguments,
            );

            if ($tokens[$index - 1]->isGivenKind(T_WHITESPACE)) {
                $forRemoveIndexes[] = $index - 1;
            }
        }

        $this->removeIndexes($tokens, $forRemoveIndexes);
    }

    private function removeIndexes(Tokens $tokens, array $indexes): void
    {
        foreach ($indexes as $index) {
            if ($index === null) {
                continue;
            }

            $tokens[$index] = new Token("");
        }
    }
}

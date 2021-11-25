<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Analyzer\ArgumentsAnalyzer;
use PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class DisableFunctionFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    protected $disableFunctions = [
        'dd' => null,
        'dump' => null,
    ];

    public function configure(array $configuration): void
    {
        if (! isset($configuration['disable_functions'])) {
            return;
        }

        $this->disableFunctions = array_flip($configuration['disable_functions']);
    }

    public function getConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder('disable_functions', 'List of disable functions.'))
                ->setDefault(['dd', 'dump'])
                ->getOption(),
        ]);
    }

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
        $funcAnalyzer = new FunctionsAnalyzer();
        $argsAnalyzer = new ArgumentsAnalyzer();

        $forRemoveIndexes = [];

        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            if (! $tokens[$index]->isGivenKind(T_STRING) ||
                ! array_key_exists($tokens[$index]->getContent(), $this->disableFunctions) ||
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

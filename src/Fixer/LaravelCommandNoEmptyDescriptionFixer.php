<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\PhpCsFixerGoodFixers\Classy\PropertyBuilder;
use ArtARTs36\Str\Str;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;

class LaravelCommandNoEmptyDescriptionFixer extends AbstractFixer
{
    public function isCandidate(Tokens $tokens): bool
    {
        return $this->helper->isClassExtends($tokens, 'Illuminate\Console\Command');
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
        $hasDescriptionProperty = false;

        foreach ($classElements as $index => $element) {
            if ($element['type'] === 'property' && $element['token']->getContent() === '$description') {
                $hasDescriptionProperty = true;

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

        if (! $hasDescriptionProperty) {
            $this->insertNewDescriptionProperty($tokens, $neededDescription, $signatureLineLastTokenIndex);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('Laravel console commands with filled descriptions', [
            new CodeSample("<?php

use Illuminate\Console\Command;

class RestartQueueCommand extends Command
{
    protected \$description = '';
}
"),
        ]);
    }

    protected function insertNewDescriptionProperty(
        Tokens $tokens,
        string $description,
        ?int $signatureLineLastTokenIndex
    ): void {
        $whiteSpaces = [$this->helper->createNewLineToken()];
        $insertIndex = null;

        if ($signatureLineLastTokenIndex) {
            $insertIndex = $signatureLineLastTokenIndex + 1;
            $whiteSpaces[] = $this->helper->createNewLineToken();
        }

        if ($insertIndex === null) {
            $classTokenIndex = $tokens->getNextTokenOfKind($this->helper->getFirstIndex($tokens), [[T_CLASS]]);
            $insertIndex = $tokens->getNextTokenOfKind($classTokenIndex, ['{']) + 1;
        }

        $propBuilder = new PropertyBuilder('description');

        $tokens->insertAt(
            $insertIndex,
            array_merge(
                $whiteSpaces,
                $propBuilder->setProtected()->setValueString($description)->buildLine()
            ),
        );
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

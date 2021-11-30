<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\PhpCsFixerGoodFixers\Doc\DocBlock;
use ArtARTs36\Str\Str;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class InterfacePhpDocSummaryFixer extends AbstractFixer
{
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_INTERFACE);
    }

    /**
     * @param Tokens&iterable<Token> $tokens
     */
    public function fix(\SplFileInfo $file, Tokens $tokens): void
    {
        $interfaceTokenIndex = $tokens->getNextTokenOfKind($this->helper->getFirstIndex($tokens), [[T_INTERFACE]]);
        $docBlockIndex = $tokens->getPrevTokenOfKind($interfaceTokenIndex, [[T_DOC_COMMENT]]);
        $nameTokenIndex = $tokens->getNextTokenOfKind($interfaceTokenIndex, [[T_STRING]]);
        $name = $tokens[$nameTokenIndex]->getContent();

        if ($docBlockIndex !== null) {
            $docBlock = DocBlock::fromTokenForClass($tokens[$docBlockIndex]);

            if ($docBlock->hasSummary()) {
                return;
            }

            $docBlock->setSummary($this->generateComment($name));

            $tokens[$docBlockIndex] = $docBlock->toToken();
        } else {
            $docBlock = DocBlock::makeWithoutSpaces();

            $docBlock->setSummary($this->generateComment($name));

            $tokens->insertAt($interfaceTokenIndex, [$docBlock->toToken(), $this->helper->createNewLineToken()]);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Each interface must have a description (PHPDoc Summary)',
            [
                new CodeSample("<?php\ninterface User\n{}"),
            ],
            'Each interface must have a description (PHPDoc Summary)'
        );
    }

    protected function generateComment(string $className): string
    {
        return Str::make($className)
            ->deleteWhenEnds('Interface')
            ->upFirstSymbol()
            ->prepend('Interface for ')
            ->append('.');
    }
}

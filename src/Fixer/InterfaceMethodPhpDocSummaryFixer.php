<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Fixer;

use ArtARTs36\PhpCsFixerGoodFixers\Doc\DocBlock;
use ArtARTs36\Str\Str;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class InterfaceMethodPhpDocSummaryFixer extends AbstractFixer
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
        $lastString = null;
        $isMethodCurrently = false;

        for ($index = $tokens->count() - 1; $index > 0; $index--) {
            if ($tokens[$index]->isGivenKind(\T_STRING)) {
                $lastString = $tokens[$index]->getContent();
            } elseif ($tokens[$index]->isGivenKind(\T_FUNCTION)) {
                $isMethodCurrently = true;
            } elseif (
                $lastString !== null
                && $isMethodCurrently
                && $tokens[$index]->isGivenKind(T_PUBLIC)
            ) {
                if ($tokens[$index - 2]->isGivenKind(T_DOC_COMMENT)) {
                    $docBlock = new DocBlock($tokens[$index - 2]->getContent());

                    if ($docBlock->hasSummary()) {
                        continue;
                    }

                    $docBlock->setSummary($this->generateComment($lastString, $file));

                    $tokens[$index - 2] = new Token([T_DOC_COMMENT, $docBlock->content()]);
                } else {
                    if ($tokens[$index - 1]->getId() === T_WHITESPACE) {
                        $tokens[$index - 1] = new Token([T_WHITESPACE, "\n    "]);
                    }

                    $tokens->insertAt(
                        $index - 1,
                        new Token(
                            [
                                T_DOC_COMMENT,
                                DocBlock::make()->setSummary($this->generateComment($lastString, $file))->content(),
                            ]
                        )
                    );

                    $tokens->insertAt($index - 1, new Token([T_WHITESPACE, "\n"]));

                    if ($tokens[$index - 2]->getContent() !== '{') {
                        $tokens->insertAt($index - 1, new Token([T_WHITESPACE, "\n"]));
                    }

                    $isMethodCurrently = false;
                }
            }
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Each interface method must have a description (PHPDoc Summary)',
            [
                new CodeSample("<?php\ninterface User\n{\npublic function getName();\n}"),
            ],
            'Each interface method must have a description (PHPDoc Summary)'
        );
    }

    protected function generateComment(string $methodName, \SplFileInfo $file): string
    {
        $methodNameWords = Str::make($methodName)->splitByDifferentCases();

        if ($methodNameWords->count() === 1) {
            return Str::make($file->getBasename())
                ->delete(['Interface'])
                ->splitByDifferentCases()
                ->first()
                ->prepend($methodName, ' ')
                ->upFirstSymbol();
        }

        return $methodNameWords->toSentence()->toLower()->upFirstSymbol();
    }
}

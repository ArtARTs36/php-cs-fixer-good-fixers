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

        if ($docBlockIndex !== null) {
            $docBlock = DocBlock::fromToken($tokens[$docBlockIndex]);

            if ($docBlock->hasSummary()) {
                return;
            }
        } else {
            $docBlock = DocBlock::make();
        }

        $docBlock->setSummary('Test class');
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
}

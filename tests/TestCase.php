<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Tests;

use ArtARTs36\PhpCsFixerGoodFixers\Fixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Tokens;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function runFixerFix(AbstractFixer $fixer, string $path): string
    {
        $tokens = Tokens::fromCode(file_get_contents($path));

       $fixer->fix(new \SplFileInfo($path), $tokens);

       return $tokens->generateCode();
    }

    protected function runFixerFixTest(AbstractFixer $fixer, string $inputPath, string $compareFilePath): void
    {
        self::assertEquals(file_get_contents($compareFilePath), $this->runFixerFix($fixer, $inputPath));
    }
}

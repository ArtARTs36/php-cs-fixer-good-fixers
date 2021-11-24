<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Tests\Unit;

use ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfaceMethodPhpDocSummaryFixer;
use ArtARTs36\PhpCsFixerGoodFixers\Tests\TestCase;

final class InterfaceMethodPhpDocSummaryFixerTest extends TestCase
{
    public function providerForTestFix(): array
    {
        return [
            [
                __DIR__ . '/../resources/interface_method_php_doc_summary/01/File.php',
                __DIR__ . '/../resources/interface_method_php_doc_summary/01/ExpectedFile.php',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestFix
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfaceMethodPhpDocSummaryFixer::fix
     */
    public function testFix(string $inputPath, string $compareFilePath): void
    {
        $this->runFixerFixTest(new InterfaceMethodPhpDocSummaryFixer(), $inputPath, $compareFilePath);
    }
}

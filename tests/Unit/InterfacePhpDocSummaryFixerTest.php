<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Tests\Unit;

use ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfacePhpDocSummaryFixer;
use ArtARTs36\PhpCsFixerGoodFixers\Tests\TestCase;

final class InterfacePhpDocSummaryFixerTest extends TestCase
{
    public function providerForTestFix(): array
    {
        return [
            [
                __DIR__ . '/../resources/interface_php_doc_summary/01/input.php',
                __DIR__ . '/../resources/interface_php_doc_summary/01/expected.php',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestFix
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfacePhpDocSummaryFixer::fix
     */
    public function testFix(string $inputPath, string $compareFilePath): void
    {
        $this->runFixerFixTest(new InterfacePhpDocSummaryFixer(), $inputPath, $compareFilePath);
    }

    /**
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\InterfacePhpDocSummaryFixer::getName
     */
    public function testGetName(): void
    {
        self::assertEquals(
            'PhpCsFixerGoodFixers/interface_php_doc_summary',
            (new InterfacePhpDocSummaryFixer())->getName()
        );
    }
}

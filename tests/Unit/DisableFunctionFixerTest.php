<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Tests\Unit;

use ArtARTs36\PhpCsFixerGoodFixers\Fixer\DisableFunctionFixer;
use ArtARTs36\PhpCsFixerGoodFixers\Tests\TestCase;

final class DisableFunctionFixerTest extends TestCase
{
    public function providerForTestFix(): array
    {
        return [
            [
                __DIR__ . '/../resources/disable_function/01/input.php',
                __DIR__ . '/../resources/disable_function/01/expected.php',
            ],
            [
                __DIR__ . '/../resources/disable_function/02/input.php',
                __DIR__ . '/../resources/disable_function/02/expected.php',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestFix
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\DisableFunctionFixer::fix
     */
    public function testFix(string $inputPath, string $expectedFilePath): void
    {
        $this->runFixerFixTest(new DisableFunctionFixer(), $inputPath, $expectedFilePath);
    }

    /**
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\DisableFunctionFixer::getName
     */
    public function testGetName(): void
    {
        self::assertEquals(
            'PhpCsFixerGoodFixers/disable_function',
            (new DisableFunctionFixer())->getName()
        );
    }
}

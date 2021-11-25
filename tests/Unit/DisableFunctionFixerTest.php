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
}

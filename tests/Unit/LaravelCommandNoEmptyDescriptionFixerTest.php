<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Tests\Unit;

use ArtARTs36\PhpCsFixerGoodFixers\Fixer\LaravelCommandNoEmptyDescriptionFixer;
use ArtARTs36\PhpCsFixerGoodFixers\Tests\TestCase;

final class LaravelCommandNoEmptyDescriptionFixerTest extends TestCase
{
    public function providerForTestFix(): array
    {
        return [
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/01.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/02.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/03.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/04.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/04_expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/05.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/06.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/06_expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/07_already_fill_property.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/07_already_fill_property.php',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestFix
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\LaravelCommandNoEmptyDescriptionFixer::fix
     */
    public function testFix(string $inputPath, string $expectedFilePath): void
    {
        $this->runFixerFixTest(new LaravelCommandNoEmptyDescriptionFixer(), $inputPath, $expectedFilePath);
    }

    /**
     * @covers \ArtARTs36\PhpCsFixerGoodFixers\Fixer\LaravelCommandNoEmptyDescriptionFixer::getName
     */
    public function testGetName(): void
    {
        self::assertEquals(
            'PhpCsFixerGoodFixers/laravel_command_no_empty_description',
            (new LaravelCommandNoEmptyDescriptionFixer())->getName()
        );
    }
}

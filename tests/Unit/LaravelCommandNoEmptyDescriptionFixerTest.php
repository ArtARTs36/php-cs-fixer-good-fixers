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
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/01_has_empty_property.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/02_has_property_without_value.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/03_has_null_property.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/04_has_null_property_without_spaces.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/04_expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/05_empty_body.php',
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/expected.php',
            ],
            [
                __DIR__ . '/../resources/laravel_command_no_empty_description/test_fix/06_has_signature_without_description.php',
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

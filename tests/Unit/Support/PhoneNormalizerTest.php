<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\PhoneNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PhoneNormalizerTest extends TestCase
{
    // normalize()

    public function test_normalize_null_returns_empty(): void
    {
        $this->assertSame('', PhoneNormalizer::normalize(null));
    }

    #[DataProvider('normalizeProvider')]
    public function test_normalize(string $input, string $expected): void
    {
        $this->assertSame($expected, PhoneNormalizer::normalize($input));
    }

    public static function normalizeProvider(): array
    {
        return [
            'with plus and spaces'    => ['+20 123 456 789', '20123456789'],
            'with plus and dashes'    => ['+20-123-456-789', '20123456789'],
            'with leading zero local' => ['0123456789', '123456789'],
            'with symbols'            => ['+20(123)456-789', '20123456789'],
            'empty string'            => ['', ''],
            'already clean'           => ['20123456789', '20123456789'],
            'multiple leading zeros'  => ['00123456789', '123456789'],
        ];
    }

    // isValid()

    #[DataProvider('isValidProvider')]
    public function test_isValid(string $input, bool $expected): void
    {
        $this->assertSame($expected, PhoneNormalizer::isValid($input));
    }

    public static function isValidProvider(): array
    {
        return [
            'egypt plus spaces'      => ['+20 123 456 789', true],
            'egypt with dashes'      => ['+20-123-456-789', true],
            'egypt plus parens'      => ['+20(123)456-789', true],
            'egypt clean'            => ['+20123456789', true],
            'no plus digit only'     => ['20123456789', true],
            'saudi arabia'           => ['+966 50 123 4567', true],
            'uk number'              => ['+44 20 7946 0958', true],
            'too short'              => ['+20123', false],
            'empty'                  => ['', false],
            'just letters'           => ['abc', false],
            'country code only'      => ['+20', false],
        ];
    }

    // getCountryCode()

    #[DataProvider('getCountryCodeProvider')]
    public function test_getCountryCode(string $input, ?string $expected): void
    {
        $this->assertSame($expected, PhoneNormalizer::getCountryCode($input));
    }

    public static function getCountryCodeProvider(): array
    {
        return [
            'egypt with spaces'      => ['+20 123 456 789', '+20'],
            'egypt with dashes'      => ['+20-123-456-789', '+20'],
            'egypt clean'            => ['+20123456789', '+20'],
            'no plus digit only'     => ['20123456789', '+20'],
            'saudi arabia'           => ['+966 50 123 4567', '+966'],
            'uk number'              => ['+44 20 7946 0958', '+44'],
            'uae number'             => ['+971 50 123 4567', '+971'],
            'usa number'             => ['+1 212 456 7890', '+1'],
            'unlisted country code'  => ['+99 123 456 789', null],
            'empty'                  => ['', null],
        ];
    }

    // consistency: normalize + isValid agree

    #[DataProvider('agreeProvider')]
    public function test_normalize_then_isValid_agree(string $input): void
    {
        $normalized = PhoneNormalizer::normalize($input);

        if (PhoneNormalizer::isValid($input)) {
            $this->assertNotEmpty($normalized);
            $this->assertMatchesRegularExpression('/^\d{7,15}$/', $normalized);
        } else {
            $this->assertTrue(true);
        }
    }

    public static function agreeProvider(): array
    {
        return [
            'egypt'           => ['+20 123 456 789'],
            'saudi'           => ['+966 50 123 4567'],
            'uk'              => ['+44 20 7946 0958'],
            'no plus'         => ['20123456789'],
            'empty'           => [''],
            'too short'       => ['+20123'],
        ];
    }
}

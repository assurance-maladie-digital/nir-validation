<?php

declare(strict_types=1);

namespace Tests\Cnamts\Nir;

use Cnamts\Nir\NirKey;
use PHPUnit\Framework\TestCase;

class NirKeyTest extends TestCase
{
    /**
     * @dataProvider validNirKeyProvider
     */
    public function testComputesCorrectKeyForValidNir(string $nir, int $expectedKey): void
    {
        $nirKey = new NirKey();

        self::assertSame($expectedKey, $nirKey->compute($nir));
    }

    public static function validNirKeyProvider(): iterable
    {
        yield ['2 55 08 14 168 025', 38];
        yield ['2 94 03 75 120 005', 91];
        yield ['1 53 12 45 007 231', 60];
        yield ['2 84 05 2A 321 025', 52];
        yield ['2 84 05 2a 321 025', 52];
        yield [' 1234 5678 9012 3 ', 11];
        yield ['284052A321025', 52];
        yield ['2B34567890123', 78];
    }
}

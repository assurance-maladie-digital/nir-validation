<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir;

use PhpSpec\ObjectBehavior;

class NirKeySpec extends ObjectBehavior
{
    private const VALID_NIR_KEYS = [
        '2 55 08 14 168 025' => 38,
        '2 94 03 75 120 005' => 91,
        '1 53 12 45 007 231' => 60,
        '2 84 05 2A 321 025' => 52,
        '2 84 05 2a 321 025' => 52,
        ' 1234 5678 9012 3 ' => 11,
        '284052A321025' => 52,
        '2B34567890123' => 78,
    ];

    public function it_computes_correct_key_for_valid_nir(): void
    {
        foreach (self::VALID_NIR_KEYS as $nir => $expected) {
            $this->compute($nir)->shouldReturn($expected);
        }
    }
}

<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Provider\Base;

class NirProvider extends Base
{
    public function generateValidNir(): string
    {
        $moisNaissance = ['1' . rand(0, 2), '0' . rand(0, 9)];
        $departementNaissance = [$this->generateNumber(2), '2A', '2B'];
        $value = rand(1, 2)
            . $this->generateNumber(2)
        . $moisNaissance[array_rand($moisNaissance)]
        . $departementNaissance[array_rand($departementNaissance)]
        . $this->generateNumber(3)
        . $this->generateNumber(3);

        return $value;
    }

    private function generateNumber(int $digit): string
    {
        $number = rand(0, 10 ** $digit);

        return str_pad(strval($number), $digit, '0', \STR_PAD_LEFT);
    }
}

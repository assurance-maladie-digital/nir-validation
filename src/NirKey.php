<?php

declare(strict_types=1);

namespace Cnamts\Nir;

class NirKey implements NirKeyInterface
{
    private const NIR_KEY = 97;

    public function compute(string $nir): int
    {
        $nir = mb_strtoupper(trim(str_replace(' ', '', $nir)));

        // Pour la Corse, remplacer 2A par 19 et 2B par 18
        $nirWithoutKey = (int) str_replace(['2A', '2B'], ['19', '18'], $nir);

        // Diviser le NIR par 97
        $step1 = floor($nirWithoutKey / self::NIR_KEY);
        // Multiplier le résultat du quotient entier de l'étape 1 par 97
        $step2 = $step1 * self::NIR_KEY;
        // Soustraire le résultat obtenu au NIR
        $step3 = $nirWithoutKey - $step2;
        // Soustraire le reste au nombre 97
        $key = self::NIR_KEY - $step3;

        return (int) $key;
    }
}

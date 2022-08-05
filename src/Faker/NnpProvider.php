<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Provider\Base;
use Faker\Provider\fr_FR\Person;

class NnpProvider extends Base
{
    public function nnp(string $gender = null): string
    {
        return $this->getGenderPart($gender)
            // le second chiffre (2ème composant) est la vérification du type de création (1 pour création et 2 pour numéros fictifs générés par le système RFI à ne pas prendre en compte)
            . $this->numberBetween(1, 2)
            // le 3ème composant est un groupe de trois chiffres correspondant au numéro de caisse
            . $this->numerify('###')
            // le 4ème composant correspond au numéro d'ordre par caisse
            . $this->numerify('########');
    }

    /* @phpstan-ignore-next-line */
    private function getGenderPart($gender): int
    {
        // Gender
        if ($gender === Person::GENDER_MALE) {
            return 7;
        }
        if ($gender === Person::GENDER_FEMALE) {
            return 8;
        }

        return $this->numberBetween(7, 8);
    }
}

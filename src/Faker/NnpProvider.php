<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Provider\Base;
use Faker\Provider\fr_FR\Person;

class NnpProvider extends Base
{
    /** @SuppressWarnings(PHPMD.ElseExpression) */
    public function nnp(string $gender = null): string
    {
        // Gender
        if ($gender === Person::GENDER_MALE) {
            $nnp = 7;
        } elseif ($gender === Person::GENDER_FEMALE) {
            $nnp = 8;
        } else {
            $nnp = $this->numberBetween(7, 8);
        }

        // le second chiffre (2ème composant) est la vérification du type de création (1 pour création et 2 pour numéros fictifs générés par le système RFI à ne pas prendre en compte)
        /** @var string $verification */
        $verification = $this->numberBetween(1, 2);
        $nnp .= $verification;

        // le 3ème composant est un groupe de trois chiffres correspondant au numéro de caisse
        $nnp .= $this->numerify('###');

        // le 4ème composant correspond au numéro d'ordre par caisse
        $nnp .= $this->numerify('########');

        return $nnp;
    }
}

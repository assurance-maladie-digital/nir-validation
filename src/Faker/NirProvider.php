<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Provider\Base;
use Faker\Provider\fr_FR\Address;
use Faker\Provider\fr_FR\Person;

class NirProvider extends Base
{
    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function nirFromParams(string $gender = null, \DateTime $date = null, string $departementCode = null): string
    {
        // Gender
        if ($gender === Person::GENDER_MALE) {
            $nir = 1;
        } elseif ($gender === Person::GENDER_FEMALE) {
            $nir = 2;
        } else {
            $nir = $this->numberBetween(1, 2);
        }

        if ($date === null) {
            $nir .=
                // Year of birth (aa)
                $this->numerify('##') .
                // Mont of birth (mm)
                sprintf('%02d', $this->numberBetween(1, 12));
        } else {
            $nir .= $date->format('ym');
        }
        // Department
        $department = $departementCode ?? (string) key(Address::department());
        $nir .= $department;

        // Town number, depends on department length
        if (mb_strlen($department) === 2) {
            $nir .= $this->numerify('###');
        } elseif (mb_strlen($department) === 3) {
            $nir .= $this->numerify('##');
        }

        // Born number (depending of town and month of birth)
        $nir .= $this->numerify('###');

        /**
         * The key for a given NIR is `97 - 97 % NIR`
         * NIR has to be an integer, so we have to do a little replacment
         * for departments 2A and 2B
         */
        if ($department === '2A') {
            $nirInteger = str_replace('2A', '19', $nir);
        } elseif ($department === '2B') {
            $nirInteger = str_replace('2B', '18', $nir);
        } else {
            $nirInteger = $nir;
        }
        $nir .= sprintf('%02d', 97 - (int) $nirInteger % 97);

        return $nir;
    }
}

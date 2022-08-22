<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Provider\fr_FR\Address;
use Faker\Provider\fr_FR\Person;

class AssureProvider extends Person
{
    /* @phpstan-ignore-next-line */
    public function nir($gender = null, $formatted = false, \DateTime $birthday = null, string $departmentCode = null): string
    {
        $departementPart = $this->getDepartementPart($departmentCode);
        $nir = $this->getGenderPart($gender)
            . $this->getDateBirthPart($birthday)
            . $departementPart
            . $this->numerify('###');

        $nir .= $this->getKeyPart($nir, $departementPart);
        // Format is x xx xx xx xxx xxx xx
        if ($formatted) {
            $nir = mb_substr($nir, 0, 1) . ' ' . mb_substr($nir, 1, 2) . ' ' . mb_substr($nir, 3, 2) . ' ' . mb_substr($nir, 5, 2) . ' ' . mb_substr($nir, 7, 3) . ' ' . mb_substr($nir, 10, 3) . ' ' . mb_substr($nir, 13, 2);
        }

        return $nir;
    }

    /* @phpstan-ignore-next-line */
    private function getGenderPart($gender): int
    {
        // Gender
        if ($gender === Person::GENDER_MALE) {
            return 1;
        }
        if ($gender === Person::GENDER_FEMALE) {
            return 2;
        }

        return $this->numberBetween(1, 2);
    }

    private function getDateBirthPart(?\DateTime $birthday): string
    {
        if ($birthday === null) {
            return
                // Year of birth (aa)
                $this->numerify('##') .
                // Month of birth (mm)
                sprintf('%02d', $this->numberBetween(1, 12));
        }

        return $birthday->format('ym');
    }

    private function getDepartmentPart(?string $departmentCode): string
    {
        // Department
        $department = $departmentCode ?? (string) key(Address::department());

        // Town number, depends on department length
        if (mb_strlen($department) === 2) {
            $department .= $this->numerify('###');
        } elseif (mb_strlen($department) === 3) {
            $department .= $this->numerify('##');
        }

        return $department;
    }

    private function getKeyPart(string $nir, string $department): string
    {
        /**
         * The key for a given NIR is `97 - 97 % NIR`
         * NIR has to be an integer, so we have to do a little replacment
         * for departments 2A and 2B
         */
        $nirInteger = $nir;
        switch ($department) {
            case '2A':
                $nirInteger = str_replace('2A', '19', $nir);
                break;
            case '2B':
                $nirInteger = str_replace('2B', '18', $nir);
                break;
        }

        return sprintf('%02d', 97 - (int) $nirInteger % 97);
    }
}

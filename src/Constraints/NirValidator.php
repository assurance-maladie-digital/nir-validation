<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Cnamts\Nir\NirKeyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NirValidator extends ConstraintValidator
{
    public const NIR_REGEX = '/\A' .
            '(?<sexe>[12])' .
            '(?<anneeNaissance>\d{2})' .
            '(?<moisNaissance>1[0-2]|0[1-9])' .
            '(?<departementNaissance>\d{2}|2A|2B)' .
            '(?<communeNaissance>\d{3})' .
            '(?<rangInscription>\d{3})' .
            '(?<cle>9[0-7]|[0-8]\d)?' .
            '\z/i';

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Nir) {
            throw new UnexpectedTypeException($constraint, Nir::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $nir = mb_strtoupper(trim(str_replace(' ', '', $value)));

        if (!in_array(mb_strlen($nir), [13, 15], true)) {
            $this->context->buildViolation($constraint->lengthMessage)
                ->setInvalidValue($value)
                ->setCode(Nir::LENGTH_ERROR)
                ->addViolation()
            ;

            return;
        }

        if (!$this->check($nir)) {
            $this->context->buildViolation($constraint->nirMessage)
                ->setInvalidValue($value)
                ->setCode(Nir::NIR_INVALID)
                ->addViolation()
            ;

            return;
        }

        if (!$this->isNirKeyValid($nir, $constraint->nirKey)) {
            $this->context->buildViolation($constraint->nirKeyMessage)
                ->setInvalidValue($value)
                ->setCode(Nir::NIR_KEY_INVALID)
                ->addViolation()
            ;
        }
    }

    /**
     * le 1er chiffre (1er composant) permet d'identifier le sexe de l'assuré (1 pour un homme et 2 pour une femme) ;
     * le second composant est un groupe de deux chiffres permettant de distinguer l'année de naissance (exemple 77 pour 1977) ;
     * le 3ème composant est un groupe de deux chiffres correspondant au mois de naissance (de 01 à 12) ;
     * le 4ème correspond au département de naissance selon le code géographique officiel (de 01 à 95 et 97 pour les DOM et COM 976, 98 pour les TOM et COM et enfin 99 pour les nés hors de France) ;
     * le 5ème est un groupe de 3 chiffres permettant d'identifier la commune de naissance, selon le code géographique officiel édité par l'INSEE (https://www.insee.fr/fr/information/5057840) ;
     * le 6ème est un groupe de 3 chiffres, correspondant au rang d'inscription sur la liste du répertoire régional de l'INSEE ;
     * le 7ème composant optionnel est un groupe de 2 chiffres appelé la clé de contrôle du NIR.
     */
    private function check(string $nir): bool
    {
        return (bool) preg_match(self::NIR_REGEX, $nir);
    }

    private function isNirKeyValid(string $nir, NirKeyInterface $nirKey): bool
    {
        if (mb_strlen($nir) === 13) {
            return true;
        }

        return ((int) mb_substr($nir, -2)) === $nirKey->compute(mb_substr($nir, 0, 13));
    }
}

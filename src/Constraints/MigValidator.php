<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MigValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Mig) {
            throw new UnexpectedTypeException($constraint, Nir::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $nnp = trim(str_replace(' ', '', $value));

        if (mb_strlen($nnp) !== 13) {
            $this->context->buildViolation($constraint->lengthMessage)
                ->setInvalidValue($value)
                ->setCode(Mig::LENGTH_ERROR)
                ->addViolation()
            ;

            return;
        }

        if (!$this->check($nnp)) {
            $this->context->buildViolation($constraint->migMessage)
                ->setInvalidValue($value)
                ->setCode(Mig::MIG_INVALID)
                ->addViolation()
            ;
        }
    }

    /**
     * le 1er chiffre (1er composant) permet d'identifier le sexe de l'assuré (5 pour un homme et 6 pour une femme) ;
     * Vérification (1 pour création au RFI, 2 pour création au moment du calage) ;
     * le 3ème composant est un groupe de trois chiffres correspondant au numéro de caisse ;
     * le 4ème correspond numéro d'ordre par caisse ;
     */
    private function check(string $nnp): bool
    {
        return (bool) preg_match(
            '/\A' .
            '(?<sexe>[56])' .
            '(?<verification>[12])' .
            '(?<numero_caisse>\d{3})' .
            '(?<numero_ordre>\d{8})' .
            '\z/i',
            $nnp
        );
    }
}

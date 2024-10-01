<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NnpValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Nnp) {
            throw new UnexpectedTypeException($constraint, Nir::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $nnp = trim(str_replace(' ', '', $value));

        if (mb_strlen($nnp) !== 13) {
            $this->context->buildViolation($constraint->lengthMessage)
                ->setInvalidValue($value)
                ->setCode(Nnp::LENGTH_ERROR)
                ->addViolation()
            ;

            return;
        }

        if (!$this->check($nnp, $constraint)) {
            $this->context->buildViolation($constraint->nnpMessage)
                ->setInvalidValue($value)
                ->setCode(Nnp::NNP_INVALID)
                ->addViolation()
            ;
        }
    }

    /**
     * le 1er chiffre (1er composant) permet d'identifier le sexe de l'assuré (7 pour un homme et 8 pour une femme) ;
     * le second chiffre (2ème composant) est la vérification du type de création (1 pour création et 2 pour numéros fictifs générés par le système RFI à ne pas prendre en compte) ;
     * le 3ème composant est un groupe de trois chiffres correspondant au numéro de caisse ;
     * le 4ème composant correspond au numéro d'ordre par caisse ;
     */
    private function check(string $nnp, Nnp $constraint): bool
    {
        // Si useBefore2012 est true, on autorise également la valeur '0' dans la partie 'verification'
        $verificationPattern = $constraint->useBefore2012 ? '[012]' : '[12]';

        return (bool) preg_match(
            '/\A' .
            '(?<sexe>[78])' .
            '(?<verification>' . $verificationPattern . ')' .
            '(?<numero_caisse>\d{3})' .
            '(?<numero_ordre>\d{8})' .
            '\z/i',
            $nnp
        );
    }
}

<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Nnp extends Constraint
{
    public const LENGTH_ERROR = 'db0f9fac-881c-4319-9abb-7bb4bc750b83';
    public const NNP_INVALID = '787ce697-1ed1-4fcb-a3d2-0492f15599ac';

    /** @var string */
    public $lengthMessage = 'Cette valeur n\'a pas la bonne longueur. Elle doit comporter 13 caractères.';

    /** @var string */
    public $nnpMessage = 'Cette valeur n\'est pas au format NNP.';

    /** @var mixed */
    protected static $errorNames = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::NNP_INVALID => 'NNP_INVALID',
    ];
}

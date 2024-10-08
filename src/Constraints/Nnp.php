<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Nnp extends Constraint
{
    public const LENGTH_ERROR = 'db0f9fac-881c-4319-9abb-7bb4bc750b83';
    public const NNP_INVALID = '787ce697-1ed1-4fcb-a3d2-0492f15599ac';

    /** @var string */
    public $lengthMessage = 'Cette valeur n\'a pas la bonne longueur. Elle doit comporter 13 caractères.';

    /** @var string */
    public $nnpMessage = 'Cette valeur n\'est pas au format NNP.';

    /** @var array<string, string> */
    protected const ERROR_NAMES = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::NNP_INVALID => 'NNP_INVALID',
    ];

    /**
     * @var array<string, string>
     * @deprecated since Symfony 6.1, use const ERROR_NAMES instead
     */
    protected static $errorNames = self::ERROR_NAMES;

    /**
     * Indique si les règles de format des NNP avant 2012 doivent être appliquées.
     *
     * Si true, le système accepte les NNP avec un 0, conformément aux
     * anciennes règles dont la trace a été perdue mais qui concernent encore
     * certains assurés.
     * Si false, seules les règles post-2012, qui ne permettent pas les NNP
     * avec un 0, seront appliquées.
     *
     * @var bool
     */
    public $useBefore2012 = false;
}

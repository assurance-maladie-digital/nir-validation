<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Mig extends Constraint
{
    public const LENGTH_ERROR = '1583444a-059d-452a-9cd2-9611cdf4fc09';
    public const MIG_INVALID = '5d7c144e-96b5-48ad-951e-ea2d9371fa7c';

    /** @var string */
    public $lengthMessage = 'Cette valeur n\'a pas la bonne longueur. Elle doit comporter 13 caractères.';

    /** @var string */
    public $migMessage = 'Cette valeur n\'est pas au format MIG.';

    /** @var array<string, string> */
    protected static $errorNames = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::MIG_INVALID => 'MIG_INVALID',
    ];
}

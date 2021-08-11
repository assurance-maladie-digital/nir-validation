<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Nir extends Constraint
{
    public const LENGTH_ERROR = '132da656-8944-475d-b502-3ca00c6c3528';
    public const NIR_INVALID = '83aac6e0-27c2-488c-9b6d-f5a42e3347d5';
    public const NIR_KEY_INVALID = 'd23783f3-9141-4a88-83be-3c0c3df340ad';

    /** @var string */
    public $lengthMessage = 'Cette valeur n\'a pas la bonne longueur. Elle doit comporter 13 ou 15 caractères.';

    /** @var string */
    public $nirMessage = 'Cette valeur n\'est pas au format NIR.';

    /** @var string */
    public $nirKeyMessage = 'La clé ne correspond pas au NIR fourni.';

    /** @var mixed */
    protected static $errorNames = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::NIR_INVALID => 'NIR_INVALID',
        self::NIR_KEY_INVALID => 'NIR_KEY_INVALID',
    ];
}

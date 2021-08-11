<?php

declare(strict_types=1);

namespace Cnamts\Nir\Constraints;

use Cnamts\Nir\Exception\InterfaceNotImplementedException;
use Cnamts\Nir\Exception\NoArgumentError;
use Cnamts\Nir\NirKey;
use Cnamts\Nir\NirKeyInterface;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
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

    /** @var array<string, string> */
    protected static $errorNames = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::NIR_INVALID => 'NIR_INVALID',
        self::NIR_KEY_INVALID => 'NIR_KEY_INVALID',
    ];

    /** @var NirKeyInterface */
    public $nirKey;

    /** @var string */
    private $classNameNirKey;

    public function __construct($options = null, array $groups = null, $payload = null, string $classNameNirKey = null)
    {
        parent::__construct($options, $groups, $payload);

        if (empty($this->classNameNirKey)) {
            $this->classNameNirKey = NirKey::class;
        }

        $this->nirKey = $this->getInstance($classNameNirKey ?? $this->classNameNirKey);
    }

    private function getInstance(string $className): NirKeyInterface
    {
        if (!is_a($className, NirKeyInterface::class, true)) {
            throw new InterfaceNotImplementedException($className, NirKeyInterface::class);
        }

        $constructor = (new ReflectionClass($className))->getConstructor();

        if ($constructor && count($constructor->getParameters()) > 0) {
            throw new NoArgumentError(count($constructor->getParameters()));
        }

        return new $className();
    }
}

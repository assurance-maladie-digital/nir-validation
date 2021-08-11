<?php

declare(strict_types=1);

namespace Cnamts\Nir\Exception;

use TypeError;

class InterfaceNotImplementedException extends TypeError
{
    public function __construct(string $class, string $interface)
    {
        $message = sprintf('%s must implements %s interface', $class, $interface);

        parent::__construct($message);
    }
}

<?php

declare(strict_types=1);

namespace Cnamts\Nir\Exception;

class NoArgumentError extends \TypeError
{
    public function __construct(int $nbParameters)
    {
        parent::__construct(sprintf('Too much arguments to the constructor, %d passed expected 0', $nbParameters));
    }
}

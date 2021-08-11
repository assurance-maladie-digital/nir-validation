<?php

declare(strict_types=1);

namespace Cnamts\Nir;

interface NirKeyInterface
{
    public function compute(string $nir): int;
}

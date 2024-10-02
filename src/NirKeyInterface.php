<?php

declare(strict_types=1);

namespace Cnamts\Nir;

interface NirKeyInterface
{
    /** Calcule la clé NIR à partir d'un NIR donné (avec ou sans la clé). */
    public function compute(string $nir): int;
}

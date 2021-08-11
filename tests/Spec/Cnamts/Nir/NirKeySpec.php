<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir;

use PhpSpec\ObjectBehavior;

class NirKeySpec extends ObjectBehavior
{
    public function it_does_not_accept_wrong_key_of_nir()
    {
        $this->compute('2 55 08 14 168 025')->shouldReturn(38);
        $this->compute('2 94 03 75 120 005')->shouldReturn(91);
        $this->compute('1 53 12 45 007 231')->shouldReturn(60);
        $this->compute('2 84 05 2A 321 025')->shouldReturn(52);
        $this->compute('2 84 05 2a 321 025')->shouldReturn(52);
        $this->compute('284052A321025')->shouldReturn(52);
    }
}

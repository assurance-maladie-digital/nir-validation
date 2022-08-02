<?php

declare(strict_types=1);

namespace Cnamts\Nir\Faker;

use Faker\Generator;
use Faker\Provider\Base;
use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;
use Hoa\Math\Sampler\Random;
use Hoa\Regex\Visitor\Isotropic;
use Hoa\Visitor\Element;

class NirProvider extends Base
{
    /**
     * @var string
     */
    private $regex = '[12]'
    . '(\d{2})'
    . '(1[0-2]|0[1-9])'
    . '(\d{2}|2A|2B)'
    . '(\d{3})'
    . '(\d{3})';

    /**
     * @var  Element
     */
    private $ast;

    /**
     * @var Isotropic
     */
    private $isotropicGenerator;

    /**
     * NirProvider constructor.
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function __construct(Generator $generator)
    {
        parent::__construct($generator);

        // 1. Load grammar.
        $compiler = Llk::load(new Read('vendor/hoa/regex/Grammar.pp'));
        // 2. Parse a data.

        /** @phpstan-ignore-next-line */
        $this->ast = $compiler->parse($this->regex);

        /** @phpstan-ignore-next-line */
        $this->isotropicGenerator = new Isotropic(new Random());
    }

    public function generateValidNir(): string
    {
        return strval($this->isotropicGenerator->visit($this->ast));
    }
}

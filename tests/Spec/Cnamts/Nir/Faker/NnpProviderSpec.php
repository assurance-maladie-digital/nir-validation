<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Faker;

use Cnamts\Nir\Constraints\Nnp;
use Cnamts\Nir\Constraints\NnpValidator;
use Faker\Generator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NnpProviderSpec extends ObjectBehavior
{
    public function let(Generator $generator)
    {
        $this->beConstructedWith($generator);
    }

    public function it_generates_valid_nnp(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $this->validateNnp($this->nnp(), $context);
    }

    public function it_generates_different_nir_on_call()
    {
        $nnp = $this->nnp();
        $nnpNext = $this->nnp();
        $nnp->shouldNotEqual($nnpNext);
    }

    /** Valide un NNP généré à partir du validateur du package */
    private function validateNnp($nnp, $context)
    {
        $constraint = new Nnp();
        $nnpValidator = new NnpValidator();
        $nnpValidator->initialize($context->getWrappedObject());
        $nnpValidator->validate($nnp->getWrappedObject(), $constraint);
    }
}

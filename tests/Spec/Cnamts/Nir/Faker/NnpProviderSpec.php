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
        $value = $this->nnp();
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $this->validateNnp($value, $context);
    }

    public function it_generates_different_nir_on_call(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $value = $this->nnp();
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $valueNext = $this->nnp();
        $this->validateNnp($value, $context);
        $this->validateNnp($valueNext, $context);
        $value->shouldNotEqual($valueNext);
    }

    /** Valide un NNP généré à partir du validateur du package */
    private function validateNnp($value, $context)
    {
        $nnp = new Nnp();
        $val = new NnpValidator();
        $val->initialize($context->getWrappedObject());
        $val->validate($value->getWrappedObject(), $nnp);
    }
}

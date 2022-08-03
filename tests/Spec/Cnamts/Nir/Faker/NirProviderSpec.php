<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Faker;

use Cnamts\Nir\Constraints\Nir;
use Cnamts\Nir\Constraints\NirValidator;
use Faker\Generator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NirProviderSpec extends ObjectBehavior
{
    public function let(Generator $generator)
    {
        $this->beConstructedWith($generator);
    }

    public function it_generates_valid_nir(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    )
    {
        $value = $this->generateValidNir();
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $this->valideNir($value, $context);
    }

    public function it_generates_different_nir_on_call(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    )
    {
        $value = $this->generateValidNir();
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $valueNext = $this->generateValidNir();
        $this->valideNir($value, $context);
        $this->valideNir($valueNext, $context);
        $value->shouldNotEqual($valueNext);
    }

    /**
     * Valide un NIR généré à partir du validateur du package
     * @param $value
     * @param $context
     */
    private function validateNir($value, $context)
    {
        $nir = new Nir();
        $val = new NirValidator();
        $val->initialize($context->getWrappedObject());
        $val->validate($value->getWrappedObject(), $nir);
    }
}

<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Nir;
use Cnamts\Nir\Constraints\NirValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NirValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NirValidator::class);
    }

    public function it_accepts_valid_nirs()
    {
        $this->validate('2840588321025', new Nir());
        $this->validate('255081416802538', new Nir());
        $this->validate('2 84 05 88 321 025', new Nir());
        $this->validate('2 84 05 88 321 025 32', new Nir());
        $this->validate('2 84 05 2A 321 025 52', new Nir());
        $this->validate('2 84 05 2a 321 025 52', new Nir());
        $this->validate('2 84 05 2B 321 025 79', new Nir());
    }

    public function it_does_not_accept_other_length(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $nir = new Nir();
        $value = '2 84 05 88 321 30';
        $context->buildViolation($nir->lengthMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Nir::LENGTH_ERROR)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $nir);
    }

    public function it_does_not_accept_wrong_nir(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $nir = new Nir();
        $value = '8 84 05 88 321 025 23';
        $context->buildViolation($nir->nirMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Nir::NIR_INVALID)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $nir);
    }

    public function it_does_not_accept_wrong_key_of_nir(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $nir = new Nir();
        $value = '2 55 08 14 168 025 00';
        $context->buildViolation($nir->nirKeyMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Nir::NIR_KEY_INVALID)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $nir);
    }

    public function it_expects_constraint_compatible_type()
    {
        $this->shouldThrow(UnexpectedTypeException::class)
            ->during('validate', ['', (new class() extends Constraint {})])
        ;
    }
}

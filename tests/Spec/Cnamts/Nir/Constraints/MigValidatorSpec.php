<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Mig;
use Cnamts\Nir\Constraints\MigValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class MigValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MigValidator::class);
    }

    public function it_accepts_valid_migs()
    {
        $this->validate('5112312345678', new Mig());
        $this->validate('6278965432189', new Mig());
        $this->validate('6144100008502', new Mig());
        $this->validate('5 1 123 12345678', new Mig());
        $this->validate('6 2 789 65432189', new Mig());
    }

    public function it_does_not_accept_wrong_length(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $mig = new Mig();
        $value = '5 2 789 654321890';
        $context->buildViolation($mig->lengthMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Mig::LENGTH_ERROR)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $mig);
    }

    public function it_does_not_accept_wrong_mig(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $mig = new Mig();
        $value = '2 2 789 65432189';
        $context->buildViolation($mig->migMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Mig::MIG_INVALID)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $mig);
    }

    public function it_expects_constraint_compatible_type()
    {
        $this->shouldThrow(UnexpectedTypeException::class)
            ->during('validate', ['', (new class() extends Constraint {})])
        ;
    }
}

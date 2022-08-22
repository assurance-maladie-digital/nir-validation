<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Nnp;
use Cnamts\Nir\Constraints\NnpValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class NnpValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NnpValidator::class);
    }

    public function it_accepts_valid_nnps()
    {
        $this->validate('7112312345678', new Nnp());
        $this->validate('8278965432189', new Nnp());
        $this->validate('7 1 123 12345678', new Nnp());
        $this->validate('8 2 789 65432189', new Nnp());
    }

    public function it_does_not_accept_wrong_length(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $nnp = new Nnp();
        $value = '8 2 789 654321890';
        $context->buildViolation($nnp->lengthMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Nnp::LENGTH_ERROR)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $nnp);
    }

    public function it_does_not_accept_wrong_nnp(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $nnp = new Nnp();
        $value = '2 2 789 65432189';
        $context->buildViolation($nnp->nnpMessage)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setInvalidValue($value)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->setCode(Nnp::NNP_INVALID)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();
        $this->initialize($context);
        $this->validate($value, $nnp);
    }

    public function it_expects_constraint_compatible_type()
    {
        $this->shouldThrow(UnexpectedTypeException::class)
            ->during('validate', ['', new class() extends Constraint {}])
        ;
    }
}

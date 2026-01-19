<?php

declare(strict_types=1);

namespace Tests\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Nnp;
use Cnamts\Nir\Constraints\NnpValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NnpValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new NnpValidator();
    }

    /**
     * @dataProvider validNnpProvider
     */
    public function testAcceptsValidNnps(string $nnp): void
    {
        $this->validator->validate($nnp, new Nnp());

        $this->assertNoViolation();
    }

    public static function validNnpProvider(): iterable
    {
        yield 'male format' => ['7112312345678'];
        yield 'female format' => ['8278965432189'];
        yield 'spaced male' => ['7 1 123 12345678'];
        yield 'spaced female' => ['8 2 789 65432189'];
    }

    public function testDoesNotAcceptWrongLength(): void
    {
        $constraint = new Nnp();
        $value = '8 2 789 654321890';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->lengthMessage)
            ->setInvalidValue($value)
            ->setCode(Nnp::LENGTH_ERROR)
            ->assertRaised()
        ;
    }

    public function testDoesNotAcceptWrongNnp(): void
    {
        $constraint = new Nnp();
        $value = '2 2 789 65432189';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->nnpMessage)
            ->setInvalidValue($value)
            ->setCode(Nnp::NNP_INVALID)
            ->assertRaised()
        ;
    }

    public function testExpectsConstraintCompatibleType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate('', new class extends Constraint {});
    }

    public function testAcceptsNnpWithZeroWhenBefore2012IsTrue(): void
    {
        $constraint = new Nnp();
        $constraint->useBefore2012 = true;

        $this->validator->validate('7012312345678', $constraint);

        $this->assertNoViolation();
    }

    public function testDoesNotAcceptNnpWithZeroWhenBefore2012IsFalse(): void
    {
        $constraint = new Nnp();
        $constraint->useBefore2012 = false;
        $value = '7012312345678';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->nnpMessage)
            ->setInvalidValue($value)
            ->setCode(Nnp::NNP_INVALID)
            ->assertRaised()
        ;
    }
}

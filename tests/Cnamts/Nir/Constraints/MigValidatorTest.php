<?php

declare(strict_types=1);

namespace Tests\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Mig;
use Cnamts\Nir\Constraints\MigValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MigValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new MigValidator();
    }

    /**
     * @dataProvider validMigProvider
     */
    public function testAcceptsValidMigs(string $mig): void
    {
        $this->validator->validate($mig, new Mig());

        $this->assertNoViolation();
    }

    public static function validMigProvider(): iterable
    {
        yield 'male format' => ['5112312345678'];
        yield 'female format' => ['6278965432189'];
        yield 'another female' => ['6144100008502'];
        yield 'spaced male' => ['5 1 123 12345678'];
        yield 'spaced female' => ['6 2 789 65432189'];
    }

    public function testDoesNotAcceptWrongLength(): void
    {
        $constraint = new Mig();
        $value = '5 2 789 654321890';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->lengthMessage)
            ->setInvalidValue($value)
            ->setCode(Mig::LENGTH_ERROR)
            ->assertRaised()
        ;
    }

    public function testDoesNotAcceptWrongMig(): void
    {
        $constraint = new Mig();
        $value = '2 2 789 65432189';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->migMessage)
            ->setInvalidValue($value)
            ->setCode(Mig::MIG_INVALID)
            ->assertRaised()
        ;
    }

    public function testExpectsConstraintCompatibleType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate('', new class extends Constraint {});
    }
}

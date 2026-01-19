<?php

declare(strict_types=1);

namespace Tests\Cnamts\Nir\Constraints;

use Cnamts\Nir\Constraints\Nir;
use Cnamts\Nir\Constraints\NirValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NirValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new NirValidator();
    }

    /**
     * @dataProvider validNirProvider
     */
    public function testAcceptsValidNirs(string $nir): void
    {
        $this->validator->validate($nir, new Nir());

        $this->assertNoViolation();
    }

    public static function validNirProvider(): iterable
    {
        yield 'standard format' => ['2840588321025'];
        yield '15 chars with key' => ['255081416802538'];
        yield 'spaced format' => ['2 84 05 88 321 025'];
        yield 'spaced with key' => ['2 84 05 88 321 025 32'];
        yield 'with Corsica 2A' => ['2 84 05 2A 321 025 52'];
        yield 'with Corsica 2B' => ['2 84 05 2B 321 025 79'];
        yield 'different department Corsica' => ['2 84 20 2B 321 025 28'];
        yield 'code 99 for foreign births' => ['2 84 99 2B 321 025 31'];
    }

    public function testDoesNotAcceptOtherLength(): void
    {
        $constraint = new Nir();
        $value = '2 84 05 88 321 30';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->lengthMessage)
            ->setInvalidValue($value)
            ->setCode(Nir::LENGTH_ERROR)
            ->assertRaised()
        ;
    }

    public function testDoesNotAcceptWrongNir(): void
    {
        $constraint = new Nir();
        $value = '8 84 05 88 321 025 23';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->nirMessage)
            ->setInvalidValue($value)
            ->setCode(Nir::NIR_INVALID)
            ->assertRaised()
        ;
    }

    public function testDoesNotAcceptWrongKeyOfNir(): void
    {
        $constraint = new Nir();
        $value = '2 55 08 14 168 025 00';

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->nirKeyMessage)
            ->setInvalidValue($value)
            ->setCode(Nir::NIR_KEY_INVALID)
            ->assertRaised()
        ;
    }

    public function testExpectsConstraintCompatibleType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate('', new class extends Constraint {});
    }
}

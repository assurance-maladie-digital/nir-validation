<?php

declare(strict_types=1);

namespace Spec\Cnamts\Nir\Faker;

use Cnamts\Nir\Constraints\Nir;
use Cnamts\Nir\Constraints\NirValidator;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class AssureProviderSpec extends ObjectBehavior
{
    /** @var Generator */
    private $faker;

    public function let(Generator $generator)
    {
        $this->faker = Factory::create('fr_FR');
        $this->beConstructedWith($generator);
    }

    public function it_generates_valid_nir(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $this->validateNir($this->nir(), $context);
    }

    public function it_generates_different_nir_on_call()
    {
        $nir = $this->nir();
        $nirNext = $this->nir();
        $nir->shouldNotEqual($nirNext);
    }

    public function it_generates_nir_from_params(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $gender = $this->faker->randomElement([Person::GENDER_MALE, Person::GENDER_FEMALE]);
        $dateNaissance = $this->faker->dateTime();
        $departement = $this->faker->departmentNumber();
        $nir = $this->nir($gender, false, $dateNaissance, $departement);
        $constraintViolationBuilder->addViolation()->shouldNotBeCalled();
        $this->validateNir($nir, $context);

        $nir->shouldbeNirFromParams($dateNaissance, $departement);
    }

    /** Valide un NIR généré à partir du validateur du package */
    private function validateNir($nir, $context)
    {
        $constraint = new Nir();
        $nirValidator = new NirValidator();
        $nirValidator->initialize($context->getWrappedObject());
        $nirValidator->validate($nir->getWrappedObject(), $constraint);
    }

    public function getMatchers(): array
    {
        return [
            'beNirFromParams' => function (string $value, \DateTime $dateNaissance, string $departement) {
                $bool = preg_match(NirValidator::NIR_REGEX, $value, $matches);
                $verifYear = $matches['moisNaissance'] === $dateNaissance->format('m');
                $verifMonth = $matches['moisNaissance'] === $dateNaissance->format('m');
                $verifDepartement = $matches['departementNaissance'] === mb_substr($departement, 0, 2);

                return $bool && $verifYear && $verifMonth && $verifDepartement;
            },
        ];
    }
}

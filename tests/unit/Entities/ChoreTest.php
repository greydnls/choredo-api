<?php

declare(strict_types=1);

namespace Choredo\Test\Entities;

use Choredo\Entities\Chore;
use Choredo\Entities\Family;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChoreTest extends TestCase
{
    /** @var Generator */
    private $faker;

    /** @var Family */
    private $family;

    public function setUp()
    {
        $this->faker  = Factory::create();
        $this->family = $this->createMock(Family::class);
    }

    public function testValidationWithInvalidName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('too long');
        (new Chore(
            Uuid::uuid4(),
            $this->family,
            str_repeat('a', 256),
            ['sunday' => true]
        ));
    }

    public function testValidationWithInvalidDescription()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('too long');
        (new Chore(
            Uuid::uuid4(),
            $this->family,
            $this->faker->firstName,
            ['sunday' => true],
            str_repeat('a', 256)
        ));
    }

    public function testValidationWithInvalidScheduleKeys()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not an element of the valid values');
        (new Chore(
            Uuid::uuid4(),
            $this->family,
            $this->faker->firstName,
            ['blorp' => true],
            $this->faker->sentence()
        ));
    }

    public function testValidationWithInvalidScheduleValues()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not a boolean');
        (new Chore(
            Uuid::uuid4(),
            $this->family,
            $this->faker->firstName,
            ['sunday' => 0],
            $this->faker->sentence()
        ));
    }
}

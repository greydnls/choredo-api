<?php

declare(strict_types=1);

namespace Choredo\Test\Entities;

use Assert\InvalidArgumentException;
use Choredo\Entities\Child;
use Choredo\Entities\Family;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChildTest extends TestCase
{
    private $faker;

    public function testConstructionWithNoAvatarUriOrColorThrowsException()
    {
        $faker  = $this->getFaker();
        $family = $this->createMock(Family::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Either 'avatarUri' or 'color' must be set");
        new Child(Uuid::uuid4(), $family, $faker->firstName);
    }

    public function testConstructionWithInvalidAvatarUri()
    {
        $faker  = $this->getFaker();
        $family = $this->createMock(Family::class);
        $badUri = $faker->text(20);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Value \"$badUri\" was expected to be a valid URL starting with http or https"
        );
        new Child(Uuid::uuid4(), $family, $faker->firstName, $badUri);
    }

    public function testConstructionWithAValidAvatarUri()
    {
        $faker  = $this->getFaker();
        $family = $this->createMock(Family::class);
        $url    = $faker->url;
        $child  = new Child(Uuid::uuid4(), $family, $faker->firstName, $url);
        $this->assertSame($url, $child->getAvatarUri());
    }

    /**
     * @param $color
     *
     * @dataProvider invalidColorProvider
     */
    public function testConstructionWithInvalidColor($color)
    {
        $faker  = $this->getFaker();
        $family = $this->createMock(Family::class);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Child::color must be a valid hexadecimal color code');
        new Child(Uuid::uuid4(), $family, $faker->firstName, null, $color);
    }

    /**
     * @param $color
     *
     * @dataProvider colorProvider
     */
    public function testConstructionWithValidColors($color)
    {
        $faker  = $this->getFaker();
        $family = $this->createMock(Family::class);
        $child  = new Child(Uuid::uuid4(), $family, $faker->firstName, null, $color);
        $this->assertSame($color, $child->getColor());
    }

    public function colorProvider()
    {
        $faker = $this->getFaker();

        $colors = [
            ['#000000'],
            ['#ffffff'],
            ['#000'],
            ['#fff'],
        ];

        for ($i = 0; $i < 100; $i++) {
            $colors[] = [$faker->hexColor];
        }

        return $colors;
    }

    public function invalidColorProvider()
    {
        return [
            ['frank'],          // lol no
            ['#a0b1c2d3'],      // too long
            ['aa00cc'],          // no leading hash
        ];
    }

    /**
     * @return Generator
     */
    private function getFaker(): Generator
    {
        if (!isset($this->faker)) {
            $this->faker = \Faker\Factory::create();
        }

        return $this->faker;
    }
}

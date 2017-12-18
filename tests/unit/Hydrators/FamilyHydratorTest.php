<?php

declare(strict_types=1);

namespace Choredo\Test\Hydrators;

use Assert\InvalidArgumentException;
use Assert\LazyAssertionException;
use Choredo\Entities;
use Choredo\Hydrators\FamilyHydrator;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\Resource;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class FamilyHydratorTest extends TestCase
{
    protected $dataStub = [
        'name'            => 'test family',
        'paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
        'weekStartDay'    => 'sunday',
    ];

    public function testHydratorGeneratesIdWhenNotProvided()
    {
        $family = (new FamilyHydrator())->hydrate($this->getResource());

        $this->assertInstanceOf(Entities\Family::class, $family);
        $this->assertInstanceOf(UuidInterface::class, $family->getId());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Value "this_is_not_a_uuid" is not a valid UUID.
     */
    public function testHydratorThrowsExceptionOnInvalidId()
    {
        (new FamilyHydrator())->hydrate($this->getResource('this_is_not_a_uuid'));
    }

    /**
     * @param $completionThreshold
     * @param $exceptionMessage
     *
     * @dataProvider invalidPaymentStrategyCompletionThresholdCombinationsProvider
     */
    public function testHydratorHandlesValidationOfPerChorePaymentStrategy($completionThreshold, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);
        (new FamilyHydrator())->hydrate(
            $this->getResource(
                'new',
                [
                    'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHILD,
                    'completionThreshold' => $completionThreshold,
                ]
            ));
    }

    public function invalidPaymentStrategyCompletionThresholdCombinationsProvider(): array
    {
        return [
            [null, 'Family::completionThreshold: Value "<NULL>" is null, but non null value was expected.'],
            [
                Entities\Family::MIN_COMPLETION_THRESHOLD - 1,
                'Family::completionThreshold: Provided "-1" is neither greater than or equal to "0" ' .
                'nor less than or equal to "100".',
            ],
            [
                Entities\Family::MAX_COMPLETION_THRESHOLD + 1,
                'Family::completionThreshold: Provided "101" is neither greater than or equal to "0" ' .
                'nor less than or equal to "100"',
            ],
        ];
    }

    /**
     * @param $name
     * @param $exceptionMessage
     *
     * @dataProvider invalidNameProvider
     */
    public function testHydratorAppliesNameValidation($name, $exceptionMessage)
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage($exceptionMessage);

        (new FamilyHydrator())->hydrate($this->getResource('new', ['name' => $name]));
    }

    public function invalidNameProvider(): array
    {
        return [
            [
                '',
                'Family::name: Value "" is too short, it should have at least 1 characters,' .
                ' but only has 0 characters.',
            ],
            [
                str_repeat('a', SHORT_DATA_FIELD_MAX_SIZE + 1),
                'Family::name: Value "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' .
                'aaaaaaaaaaaaaaaaaaaaaa..." is too long, it should have no more than 255 characters, but has 256 ' .
                'characters.',
            ],
        ];
    }

    public function testHydratorValidatesPaymentStrategyForPerChoreWithNullCompletionThreshold()
    {
        $family = (new FamilyHydrator())->hydrate(
            $this->getResource('new', ['paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHORE])
        );

        $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHORE, $family->getPaymentStrategy());
        $this->assertNull($family->getCompletionThreshold());
    }

    public function testHydratorValidatesPaymentStrategyPerChildWithCompletionThreshold()
    {
        $family = (new FamilyHydrator())->hydrate(
            $this->getResource(
                'new',
                [
                    'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
                    'completionThreshold' => 100,
                ]
            ));

        $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHORE, $family->getPaymentStrategy());
        $this->assertEquals(100, $family->getCompletionThreshold());
    }

    public function testHydratorThrowsExceptionWithInvalidStrategy()
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            'Family::paymentStrategy: Value "this is not a real strategy" is not an element of' .
            ' the valid values: per_child, per_chore'
        );
        (new FamilyHydrator())->hydrate($this->getResource('new', ['paymentStrategy' => 'this is not a real strategy']));
    }

    public function testHydratorThrowsExceptionOnInvalidWeekStartDay()
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            'Family::weekStartDay: Value "this is not a real day" is not an element of the valid values: sunday,' .
            ' monday, tuesday, wednesday, thursday, friday, saturday'
        );
        (new FamilyHydrator())->hydrate(
            $this->getResource('new', ['weekStartDay' => 'this is not a real day']));
    }

    private function getResource($id = JsonApiResource::TYPE_NEW, $data = [])
    {
        $data = array_merge($this->dataStub, $data);

        return new Resource($id, 'family', $data);
    }
}

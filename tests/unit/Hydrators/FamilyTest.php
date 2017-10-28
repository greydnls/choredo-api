<?php

namespace Choredo\Test\Hydrators;

use Assert\LazyAssertionException;
use Choredo\Entities;
use Choredo\Hydrators;
use PHPUnit\Framework\TestCase;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class FamilyTest extends TestCase
{
    protected $dataStub = [
        'name'            => 'test family',
        'paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHILD,
        'weekStartDay'    => 'sunday',
    ];

    public function testHydratorGeneratesIdWhenNotProvided()
    {
        $data = $this->dataStub;

        $hydrator = new Hydrators\Family();
        $family = $hydrator->hydrate($data);

        $this->assertInstanceOf(Entities\Family::class, $family);
        $this->assertNotEmpty($family->getId());
    }

    /**
     * @expectedException \Assert\LazyAssertionException
     * @expectedExceptionMessage Family::id: Value "this_is_not_a_uuid" is not a valid UUID.
     */
    public function testHydratorThrowsExceptionOnInvalidId()
    {
        $data = array_merge($this->dataStub, ['id' => 'this_is_not_a_uuid']);
        $hydrator = new Hydrators\Family();
        $hydrator->hydrate($data);
    }

    /**
     * @param $completionThreshold
     * @param $exceptionMessage
     *
     * @dataProvider invalidPaymentStrategyCompletionThresholdCombinationsProvider
     */
    public function testHydratorHandlesValidationOfPerChorePaymentStrategy($completionThreshold, $exceptionMessage)
    {
        $data = array_merge(
            $this->dataStub,
            [
                'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
                'completionThreshold' => $completionThreshold,
            ]
        );

        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage($exceptionMessage);
        (new Hydrators\Family())->hydrate($data);
    }

    public function invalidPaymentStrategyCompletionThresholdCombinationsProvider(): array
    {
        return [
            [null, "Family::completionThreshold: Value \"<NULL>\" is empty, but non empty value was expected."],
            [0, "Family::completionThreshold: Value \"0\" is empty, but non empty value was expected."],
            [
                Entities\Family::MIN_COMPLETION_THRESHOLD - 1,
                "Family::completionThreshold: Provided \"-1\" is neither greater than or equal to \"0\" " .
                "nor less than or equal to \"100\".",
            ],
            [
                Entities\Family::MAX_COMPLETION_THRESHOLD + 1,
                "Family::completionThreshold: Provided \"101\" is neither greater than or equal to \"0\" " .
                "nor less than or equal to \"100\"",
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
        $data = array_merge($this->dataStub, ['name' => $name]);
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage($exceptionMessage);
        (new Hydrators\Family())->hydrate($data);
    }

    public function invalidNameProvider(): array
    {
        return [
            [
                "",
                "Family::name: Value \"\" is too short, it should have at least 1 characters," .
                " but only has 0 characters.",
            ],
            [
                str_repeat('a', SHORT_DATA_FIELD_MAX_SIZE + 1),
                "Family::name: Value \"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa" .
                "aaaaaaaaaaaaaaaaaaaaaa...\" is too long, it should have no more than 255 characters, but has 256 " .
                "characters.",
            ],
        ];
    }

    public function testHydratorValidatesPaymentStrategy()
    {
        $data = array_merge($this->dataStub, ['paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHILD]);
        $family = (new Hydrators\Family())->hydrate($data);
        $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHILD, $family->getPaymentStrategy());
        $this->assertNull($family->getCompletionThreshold());

        $data = array_merge(
            $this->dataStub,
            [
                'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
                'completionThreshold' => 100,
            ]
        );
        $family = (new Hydrators\Family())->hydrate($data);
        $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHORE, $family->getPaymentStrategy());
        $this->assertEquals(100, $family->getCompletionThreshold());

        $data = array_merge($this->dataStub, ['paymentStrategy' => "this is not a real strategy"]);
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            "Family::paymentStrategy: Value \"this is not a real strategy\" is not an element of" .
            " the valid values: per_child, per_chore"
        );
        (new Hydrators\Family())->hydrate($data);
    }

    public function testHydratorThrowsExceptionOnInvalidWeekStartDay()
    {
        $data = array_merge($this->dataStub, ['weekStartDay' => 'this is not a real day']);
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            "Family::weekStartDay: Value \"this is not a real day\" is not an element of the valid values: sunday," .
            " monday, tuesday, wednesday, thursday, friday, saturday"
        );
        (new Hydrators\Family())->hydrate($data);
    }
}

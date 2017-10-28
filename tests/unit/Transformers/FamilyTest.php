<?php

namespace Choredo\Test\Transformers;

use Choredo\Entities;
use Choredo\Transformers;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FamilyTest extends TestCase
{
    /**
     * @param Uuid $id
     * @param string $name
     * @param $paymentStrategy
     * @param $weekStartDay
     * @param $weekStartDayText
     *
     * @dataProvider weekStartDayProvider
     */
    public function testDayOfWeekConversion(Uuid $id, string $name, $paymentStrategy, $weekStartDay, $weekStartDayText)
    {
        $family = new Entities\Family($id, $name, $paymentStrategy, $weekStartDay);

        $transformer = new Transformers\Family();
        $resource = $transformer->transform($family);

        $this->assertSame($weekStartDayText, $resource['attributes']['weekStartDay']);
    }

    public function weekStartDayProvider(): array
    {
        return [
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 0, 'sunday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 1, 'monday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 2, 'tuesday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 3, 'wednesday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 4, 'thursday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 5, 'friday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHILD, 6, 'saturday'],
        ];
    }
}

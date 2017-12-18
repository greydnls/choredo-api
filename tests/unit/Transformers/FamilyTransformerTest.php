<?php

declare(strict_types=1);

namespace Choredo\Test\Transformers;

use Choredo\Entities;
use Choredo\Transformers;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FamilyTransformerTest extends TestCase
{
    /**
     * @param Uuid   $id
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
        $family->updateCreatedDate();
        $family->updateUpdatedDate();

        $transformer = new Transformers\FamilyTransformer();
        $resource    = $transformer->transform($family);

        $this->assertSame($weekStartDayText, $resource['weekStartDay']);
    }

    public function weekStartDayProvider(): array
    {
        return [
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 0, 'sunday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 1, 'monday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 2, 'tuesday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 3, 'wednesday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 4, 'thursday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 5, 'friday'],
            [Uuid::uuid4(), 'test family', Entities\Family::PAYMENT_STRATEGY_PER_CHORE, 6, 'saturday'],
        ];
    }
}

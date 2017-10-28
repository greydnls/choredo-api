<?php

namespace Choredo\Hydrators;

use Assert\Assert;
use Choredo\Entities;
use Ramsey\Uuid\Uuid;
use const Choredo\DAYS_OF_WEEK;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class Family
{
    public function hydrate(array $data): Entities\Family
    {
        $data['id'] = $data['id'] ?? Uuid::uuid4();
        $this->validateData($data);

        return new Entities\Family(
            $data['id'],
            $data['name'],
            $data['paymentStrategy'],
            array_search($data['weekStartDay'], DAYS_OF_WEEK),
            $data['completionThreshold']
        );
    }

    private function validateData(array $data): void
    {
        Assert::lazy()
            ->that($data['id'], 'Family::id')->uuid()
            ->that($data['name'], 'Family::name')
                ->tryAll()->string()->minLength(1)->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
            ->that($data['paymentStrategy'], 'Family::paymentStrategy')
                ->choice([
                    Entities\Family::PAYMENT_STRATEGY_PER_CHILD,
                    Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
                ])
            ->that($data['weekStartDay'], 'Family::weekStartDay')
                ->choice(DAYS_OF_WEEK)
            ->that($data['completionThreshold'], 'Family::completionThreshold')
                ->nullOr()
                ->tryAll()
                    ->integer()
                    ->min(Entities\Family::MIN_COMPLETION_THRESHOLD)
                    ->max(Entities\Family::MAX_COMPLETION_THRESHOLD)
            ->verifyNow();
    }
}

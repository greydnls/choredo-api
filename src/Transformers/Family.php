<?php

namespace Choredo\Transformers;

use Assert\Assertion;
use Choredo\Entities;
use League\Fractal\TransformerAbstract;

class Family extends TransformerAbstract
{
    const DAYS_OF_WEEK = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    public function transform(Entities\Family $family)
    {
        return [
            'type'       => 'family',
            'id'         => $family->getId()->toString(),
            'attributes' => [
                'name'                => $family->getName(),
                'paymentStrategy'     => $family->getPaymentStrategy(),
                'completionThreshold' => $family->getCompletionThreshold(),
                'weekStartDay'        => $this->transformWeekStartDay($family->getWeekStartDay()),
            ],
            'links'      => [
                'self' => '/family/' . $family->getId()->toString(),
            ],
        ];
    }

    private function transformWeekStartDay(int $weekStartDay): string
    {
        Assertion::between($weekStartDay, 0, count(static::DAYS_OF_WEEK) - 1);

        return static::DAYS_OF_WEEK[$weekStartDay];
    }
}

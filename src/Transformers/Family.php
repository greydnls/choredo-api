<?php

namespace Choredo\Transformers;

use Assert\Assertion;
use Choredo\Entities;
use League\Fractal\TransformerAbstract;
use const Choredo\DAYS_OF_WEEK;

class Family extends TransformerAbstract
{
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
        Assertion::between($weekStartDay, 0, count(DAYS_OF_WEEK) - 1);

        return DAYS_OF_WEEK[$weekStartDay];
    }
}

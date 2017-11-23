<?php

namespace Choredo\Transformers;

use Assert\Assertion;
use Choredo\Entities;
use League\Fractal\TransformerAbstract;
use const Choredo\DAYS_OF_WEEK;

class FamilyTransformer extends TransformerAbstract
{
    public function transform(Entities\Family $family)
    {
        $entity = [
            'id'                  => $family->getId()->toString(),
            'name'                => $family->getName(),
            'paymentStrategy'     => $family->getPaymentStrategy(),
            'completionThreshold' => $family->getCompletionThreshold(),
            'weekStartDay'        => $this->transformWeekStartDay($family->getWeekStartDay()),
        ];

        if ($family->getCreatedDate()) {
            $entity['createdDate'] = $family->getCreatedDate()->format(\DateTime::ATOM);
        }

        if ($family->getUpdateDate()) {
            $entity['updatedDate'] = $family->getUpdateDate()->format(\DateTime::ATOM);
        }

        return $entity;
    }

    private function transformWeekStartDay(int $weekStartDay): string
    {
        Assertion::between($weekStartDay, 0, count(DAYS_OF_WEEK) - 1);

        return DAYS_OF_WEEK[$weekStartDay];
    }
}

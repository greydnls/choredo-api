<?php


namespace Choredo\Hydrators;

use Assert\Assert;
use Assert\Assertion;
use Choredo\JsonApi\JsonApiResource;
use Choredo\Entities;
use Ramsey\Uuid\Uuid;

use const Choredo\DAYS_OF_WEEK;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class FamilyHydrator implements Hydrator
{
    public function hydrate(JsonApiResource $resource)
    {
        $this->validateResource($resource);

        return new Entities\Family(
            $resource->getId() === JsonApiResource::TYPE_NEW ? Uuid::uuid4() : Uuid::fromString($resource->getId()),
            $resource->getAttribute('name'),
            $resource->getAttribute('paymentStrategy'),
            array_search($resource->getAttribute('weekStartDay'), DAYS_OF_WEEK),
            $resource->getAttribute('completionThreshold')
        );
    }

    private function validateResource(JsonApiResource $resource): void
    {
        if ($resource->getId() !== JsonApiResource::TYPE_NEW) {
            Assertion::uuid($resource->getId());
        }

        Assert::lazy()
            ->that($resource->getAttribute('name'), 'Family::name')
            ->minLength(1)->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
            ->that($resource->getAttribute('paymentStrategy'), 'Family::paymentStrategy')
            ->choice([
                Entities\Family::PAYMENT_STRATEGY_PER_CHILD,
                Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
            ])
            ->that($resource->getAttribute('weekStartDay'), 'Family::weekStartDay')
            ->choice(DAYS_OF_WEEK)
            ->that($resource->getAttribute('completionThreshold'), 'Family::completionThreshold')
            ->nullOr()
            ->between(
                Entities\Family::MIN_COMPLETION_THRESHOLD,
                Entities\Family::MAX_COMPLETION_THRESHOLD
            )
            ->verifyNow();

        if ($resource->getAttribute('paymentStrategy') === Entities\Family::PAYMENT_STRATEGY_PER_CHILD) {
            Assert::lazy()
                ->that($resource->getAttribute('completionThreshold'), 'Family::completionThreshold')
                ->notNull()
                ->between(
                    Entities\Family::MIN_COMPLETION_THRESHOLD,
                    Entities\Family::MAX_COMPLETION_THRESHOLD
                )
                ->verifyNow();
        }
    }
}
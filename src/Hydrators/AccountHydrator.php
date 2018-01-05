<?php

declare(strict_types=1);

namespace Choredo\Hydrators;

use Assert\Assert;
use Assert\Assertion;
use Choredo\Entities\Account;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\ResourceIdGenerator;
use Ramsey\Uuid\Uuid;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class AccountHydrator implements Hydrator
{
    /**
     * @var FamilyHydrator
     */
    private $familyHydrator;

    public function __construct(FamilyHydrator $familyHydrator = null)
    {
        $this->familyHydrator = $familyHydrator ?? new FamilyHydrator();
    }

    public function hydrate(JsonApiResource $resource)
    {
        $this->validateResource($resource);

        $family = $this->familyHydrator->hydrate(
            $resource->getRelationship('family')
        );

        return new Account(
            ResourceIdGenerator::generateId($resource),
            $resource->getAttribute('email'),
            $resource->getAttribute('firstName'),
            $resource->getAttribute('lastName'),
            $resource->getAttribute('avatarUri'),
            $resource->getAttribute('token'),
            new \DateTime('now'),
            $family
        );
    }

    private function validateResource(JsonApiResource $resource): void
    {
        if ($resource->getId() !== JsonApiResource::TYPE_NEW) {
            Assertion::uuid($resource->getId());
        }

//        Assert::lazy()
//            ->that($resource->getAttribute('name'), 'Family::name')
//            ->minLength(1)->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
//            ->verifyNow();
    }
}

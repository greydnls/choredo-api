<?php

declare(strict_types=1);

namespace Choredo\Hydrators;

use Assert\Assert;
use Assert\Assertion;
use Choredo\Entities;
use Choredo\FamilyAware;
use Choredo\FamilyAwareTrait;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\ResourceIdGenerator;
use const Choredo\HEX_COLOR_REGEX;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class ChildHydrator implements Hydrator, FamilyAware
{
    use FamilyAwareTrait;

    /**
     * @var \Choredo\Entities\Family
     */
    private $family;

    public function hydrate(JsonApiResource $resource)
    {
        $this->validateResource($resource);

        return new Entities\Child(
            ResourceIdGenerator::generateId($resource),
            $this->family,
            $resource->getAttribute('name'),
            $resource->getAttribute('avatarUri'),
            $resource->getAttribute('color'),
            $resource->getAttribute('accessCode')
        );
    }

    private function validateResource(JsonApiResource $resource): void
    {
        if ($resource->getId() !== JsonApiResource::TYPE_NEW) {
            Assertion::uuid($resource->getId());
        }

        Assert::lazy()
              ->that($resource->getAttribute('name'), 'Child::name')
              ->minLength(1)
              ->maxLength(SHORT_DATA_FIELD_MAX_SIZE)
              ->that($resource->getAttribute('avatarUri'), 'Child::avatarUri')
              ->nullOr()
              ->url()
              ->that($resource->getAttribute('color'), 'Child::color')
              ->nullOr()
              ->regex(HEX_COLOR_REGEX)
              ->that($resource->getAttribute('accessCode'), 'Child::accessCode')
              ->nullOr()
              ->string()
              ->alnum()
              ->length(6)
              ->verifyNow()
        ;
    }
}

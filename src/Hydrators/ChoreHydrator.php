<?php

declare(strict_types=1);

namespace Choredo\Hydrators;

use Choredo\Entities\Chore;
use Choredo\FamilyAware;
use Choredo\FamilyAwareTrait;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\ResourceIdGenerator;

class ChoreHydrator implements Hydrator, FamilyAware
{
    use FamilyAwareTrait;

    /**
     * @param \Choredo\JsonApi\JsonApiResource $resource
     *
     * @return mixed
     */
    public function hydrate(JsonApiResource $resource)
    {
        return new Chore(
            ResourceIdGenerator::generateId($resource),
            $this->getFamily(),
            $resource->getAttribute('name'),
            $resource->getAttribute('schedule'),
            $resource->getAttribute('description'),
            $resource->getAttribute('value')
        );
    }
}

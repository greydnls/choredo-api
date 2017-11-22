<?php


namespace Choredo\Hydrators;

use Choredo\JsonApi\JsonApiResource;

interface Hydrator
{
    public function hydrate(JsonApiResource $resource);

    public function getAttributeName(): string;
}
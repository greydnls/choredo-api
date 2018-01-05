<?php

declare(strict_types=1);

namespace Choredo\JsonApi;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ResourceIdGenerator
{
    public static function generateId(JsonApiResource $resource): UuidInterface
    {
        $id = $resource->getId();

        return $id === JsonApiResource::TYPE_NEW ? Uuid::uuid4() : Uuid::fromString($id);
    }
}

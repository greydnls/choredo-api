<?php

declare(strict_types=1);

namespace Choredo\JsonApi;

class Relation implements JsonApiResource
{
    /**
     * @var JsonApiResource
     */
    private $resource;
    /**
     * @var bool
     */
    private $isLoaded;

    /**
     * Relationship constructor.
     *
     * @param JsonApiResource $resource
     * @param bool            $isLoaded
     */
    public function __construct(JsonApiResource $resource, bool $isLoaded = true)
    {
        $this->resource = $resource;
        $this->isLoaded = $isLoaded;
    }

    /**
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }

    public function getId()
    {
        return $this->resource->getId();
    }

    public function getType(): string
    {
        return $this->resource->getType();
    }

    public function getAttributes(): array
    {
        return $this->resource->getAttributes();
    }

    public function getAttribute(string $key, $default = null)
    {
        return $this->resource->getAttribute($key, $default);
    }

    public function hasAttribute(string $key): bool
    {
        return $this->resource->hasAttribute($key);
    }

    public function hasRelationship($name): bool
    {
        return $this->resource->hasRelationship($name);
    }

    public function getRelationship($name, $default = [])
    {
        return $this->resource->getRelationship($name, $default);
    }

    public function getRelationships(): array
    {
        return $this->resource->getRelationships();
    }
}

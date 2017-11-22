<?php

namespace Choredo\JsonApi;

class CompoundDocument implements JsonApiResource
{
    /**
     * @var JsonApiResource
     */
    private $resource;

    /**
     * @var array
     */
    private $included;

    /**
     * CompoundDocument constructor.
     * @param JsonApiResource $resource
     * @param array $includedData
     */
    public function __construct(JsonApiResource $resource, array $includedData)
    {
        $this->resource = $resource;
        $this->included = $includedData;
    }

    public function getRelationshipResource(Relation $relation): ?JsonApiResource
    {
        $relationship = array_filter($this->included, function(JsonApiResource $included) use ($relation){
            return $included->getId() === $relation->getId() && $included->getType() == $relation->getType();
        });

        if ($relationship instanceof JsonApiResource){
            return $relationship;
        }

        throw new \InvalidArgumentException('Invalid Related Resource Requested');
    }

    public function hasRelatedResource(Relation $relation) : bool
    {
        return array_filter($this->included, function(JsonApiResource $included) use ($relation){
            return $included->getId() === $relation->getId() && $included->getType() == $relation->getType();
        }) instanceof JsonApiResource;
    }

    public function getRelatedResources(): array
    {
        return $this->included;
    }

    public function hasRelationship($name): boolean
    {
        return $this->resource->hasRelationship($name);
    }

    public function getRelationship($name): array
    {
        return $this->resource->getRelationship($name);
    }

    public function getRelationships(): array
    {
       return $this->resource->getRelationships();
    }

    public function getId()
    {
        $this->resource->getId();
    }

    public function getType(): string
    {
        $this->resource->getType();
    }

    public function getAttributes(): array
    {
        $this->resource->getAttributes();
    }

    public function getAttribute(string $key, $default = null)
    {
        $this->resource->getAttribute($key, $default);
    }

    public function hasAttribute(string $key): bool
    {
        $this->resource->hasAttribute($key);
    }
}
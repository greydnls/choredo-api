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

    public function getRelatedResource(Relation $relation): ?JsonApiResource
    {
        $relationship = $this->filterIncludedByRelation($relation);

        if ($relationship instanceof JsonApiResource) {
            return $relationship;
        }

        throw new \InvalidArgumentException('Invalid Related Resource Requested');
    }

    public function hasRelatedResource(Relation $relation): bool
    {
        return $this->filterIncludedByRelation($relation) instanceof JsonApiResource;
    }

    public function getRelatedResources(): array
    {
        return $this->included;
    }

    public function hasRelationship($name): bool
    {
        return $this->resource->hasRelationship($name);
    }

    public function getRelationship($name, $default = [])
    {
        return $this->resource->getRelationship($name, $default = []);
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

    /**
     * @param Relation $relation
     *
     * @return JsonApiResource|null
     */
    private function filterIncludedByRelation(Relation $relation): ?JsonApiResource
    {
        $relationMatches = function (JsonApiResource $included) use ($relation) {
            return $included->getId() === $relation->getId() && $included->getType() == $relation->getType();
        };

        return array_pop(
            array_filter($this->included, $relationMatches)
        );
    }
}
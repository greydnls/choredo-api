<?php

namespace Choredo;

class CompoundDocument implements JsonApiResource
{
    /**
     * @var JsonApiResource
     */
    private $resource;

    /**
     * @var array
     */
    private $relationships;
    /**
     * @var array
     */
    private $included;

    /**
     * CompoundDocument constructor.
     * @param JsonApiResource $resource
     * @param array $relationships
     */
    public function __construct(JsonApiResource $resource, array $relationships)
    {
        $this->resource = $resource;
        $this->relationships = $relationships;
    }

    public function hasRelationship($name): boolean
    {
        return array_key_exists($name, $this->relationships);
    }

    public function getRelationship($name): array
    {
        return array_filter($this->relationships, function($relationship) use ($name) {
           return $relationship->name === $name;
        });
    }

    public function getRelatedResource($name, $id): ?JsonApiResource
    {
        $relationship = $this->getRelationship($name);

        if (is_array($relationship)) {
            $relationship = array_filter($relationship, function(JsonApiResource $relationship) use ($id){
                return $relationship->getId() === $id;
            });
        }

        if ($relationship instanceof JsonApiResource && $relationship->getId() === $id){
            return $relationship;
        }

        throw new \InvalidArgumentException('Invalid Related Resource Requested');
    }

    public function getRelatedResources(): array
    {
        array_reduce($this->relationships, function($included, Relationship $relationship){
            return array_merge($included, (array)$relationship->getData());
        }, []);
    }

    public function getRelationships(): array
    {
       return $this->relationships;
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
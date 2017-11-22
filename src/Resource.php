<?php


namespace Choredo;

class Resource implements JsonApiResource
{
    private $id;
    /**
     * @var string
     */
    private $type;
    /**
     * @var array
     */
    private $attributes;

    public function __construct(
        string $id,
        string $type,
        array $attributes
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key, $default = null)
    {
        return $this->hasAttribute($key)
            ? $this->attributes[$key]
            : $default;
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function hasRelationship($name): boolean
    {
        return false;
    }

    public function getRelatedResource($name, $id): JsonApiResource
    {
        return null;
    }

    public function getRelationship($name)
    {
        return false;
    }

    public function getRelatedResources() : array
    {
        return [];
    }

    public function getRelationships() : array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace Choredo\JsonApi;

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
    /**
     * @var array
     */
    private $relationships;

    public function __construct(
        string $id,
        string $type,
        array $attributes,
        array $relationships = []
    ) {
        $this->id         = $id;
        $this->type       = $type;
        $this->attributes = $attributes;
        $this->setRelationships($relationships);
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

    public function hasRelationship($name): bool
    {
        return array_key_exists($name, $this->relationships);
    }

    public function getRelationship($name, $default = [])
    {
        return $this->hasRelationship($name)
            ? $this->relationships[$name]
            : $default;
    }

    public function getRelationships(): array
    {
        return $this->relationships;
    }

    public function getRelatedResource(Relation $relation): ?JsonApiResource
    {
        return null;
    }

    public function hasRelatedResource(Relation $relation): bool
    {
        return false;
    }

    public function getRelatedResources(): array
    {
        return [];
    }

    /**
     * @param array $relationships
     */
    private function setRelationships(array $relationships): void
    {
        foreach ($relationships as $name => $relationship) {
            $relationship = is_array($relationship) ? $relationship : [$relationship];
            foreach ($relationship as $relation) {
                if (!$relation instanceof Relation && null !== $relation) {
                    throw new \InvalidArgumentException('Invalid ' . $name . ' Relationship provided');
                }
            }
        }

        $this->relationships = $relationships;
    }
}

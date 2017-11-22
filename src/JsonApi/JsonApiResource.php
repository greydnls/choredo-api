<?php


namespace Choredo\JsonApi;


interface JsonApiResource
{
    public function getId();

    public function getType(): string;

    public function getAttributes(): array;

    public function getAttribute(string $key, $default = null);

    public function hasAttribute(string $key) : bool;

    public function hasRelationship($name): boolean;

    public function getRelatedResource($name, $id): ?JsonApiResource;

    public function getRelationship($name);

    public function getRelatedResources() : array;

    public function getRelationships() : array;
}
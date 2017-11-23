<?php


namespace Choredo\JsonApi;


interface JsonApiResource
{
    const TYPE_UUID = 'uuid';
    const TYPE_NEW = 'new';

    public function getId();

    public function getType(): string;

    public function getAttributes(): array;

    public function getAttribute(string $key, $default = null);

    public function hasAttribute(string $key) : bool;

    public function hasRelationship($name): bool;

    public function getRelatedResource(Relation $relation): ?JsonApiResource;

    public function hasRelatedResource(Relation $relation): bool;

    public function getRelationship($name, $default = []);

    public function getRelatedResources() : array;

    public function getRelationships() : array;
}
<?php

declare(strict_types=1);

namespace Choredo\JsonApi;

interface JsonApiResource
{
    const TYPE_UUID = 'uuid';
    const TYPE_NEW  = 'new';

    public function getId();

    public function getType(): string;

    public function getAttributes(): array;

    public function getAttribute(string $key, $default = null);

    public function hasAttribute(string $key): bool;

    public function hasRelationship($name): bool;

    public function getRelationship($name, $default = []);

    public function getRelationships(): array;
}

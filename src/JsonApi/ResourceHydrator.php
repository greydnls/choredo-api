<?php

namespace Choredo\JsonApi;

use Assert\Assert;
use Ramsey\Uuid\Uuid;

class ResourceHydrator
{
    public static function instance()
    {
        return new self;
    }
    public function hydrate(string $expectedType, string $idType, array $data)
    {
        Assert::that($data)->keyExists('data');
        $parsedBody = $data['data'];

        $this->validateResource($expectedType, $idType, $parsedBody);

        return $this->hydrateResource($parsedBody, $data['included'] ?? []);
    }

    private function hydrateRelations(array $relationships)
    {
        return array_map(function ($relation) {
            $hydrateRelation = function (array $relation) {
                return new Relation($relation['type'], $relation['id']);
            };

            return (array_key_exists('id', $relation['data']))
                ? $hydrateRelation($relation['data'])
                : array_map($hydrateRelation, $relation['data']);
        }, $relationships);
    }

    private function hydrateIncluded(array $included)
    {
        return array_map(function ($includedResource) {
            return $this->hydrateResource($includedResource);
        }, $included);
    }

    /**
     * @param $parsedBody
     * @param array $includedData
     *
     * @return JsonApiResource
     */
    private function hydrateResource($parsedBody, $includedData = []) : JsonApiResource
    {
        $resource = new Resource(
            $parsedBody['id'],
            $parsedBody['type'],
            $parsedBody['attributes'],
            $this->hydrateRelations($parsedBody['relationships'] ?? [])
        );

        if (!empty($includedData)) {
            $resource = new CompoundDocument($resource, $this->hydrateIncluded($includedData));
        }
        return $resource;
    }

    private function validateResource($expectedType, $idType, $parsedBody)
    {
        Assert::lazy()
            ->that($parsedBody, 'request::body')
            ->keyExists('attributes')
            ->keyExists('id')
            ->keyExists('type')
            ->that($parsedBody['attributes'], 'request::body::attributes')
            ->isArray()
            ->that($parsedBody['type'], 'request::body::type')
            ->eq($expectedType)
            ->verifyNow();

        if ($idType === JsonApiResource::TYPE_UUID) {
            Assert::lazy()
                ->that($parsedBody['id'], 'request::body::id')
                ->uuid()
                ->verifyNow();

            $parsedBody['id'] = Uuid::fromString($parsedBody['id']);
        } else {
            Assert::lazy()
                ->that($parsedBody['id'], 'request::body::id')
                ->eq(JsonApiResource::TYPE_NEW)
                ->verifyNow();
        }

    }
}
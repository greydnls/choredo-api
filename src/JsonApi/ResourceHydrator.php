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

    private function hydrateRelations(array $relationships, array $includedData)
    {
        return array_map(function ($relation) use ($includedData) {
            if (empty($relation['data'])) {
                return null;
            }
            $hydrateRelation = function (array $relation) use ($includedData) {
                $included = array_filter($includedData, function ($included) use ($relation) {
                    return $included['id'] === $relation['id'] && $included['type'] == $relation['type'];
                });

                [$relation, $isLoaded] = (!empty($included))
                    ? [current($included), true]
                    : [$relation, false];

                return  new Relation($this->hydrateResource($relation), $isLoaded);
            };

            return (array_key_exists('id', $relation['data']))
                ? $hydrateRelation($relation['data'])
                : array_map($hydrateRelation, $relation['data']);
        }, $relationships);
    }

    /**
     * @param $parsedBody
     * @param array $includedData
     *
     * @return JsonApiResource
     */
    private function hydrateResource(array $parsedBody, $includedData = []): JsonApiResource
    {
        $relations = $this->hydrateRelations($parsedBody['relationships'] ?? [], $includedData);

        return new Resource(
            $parsedBody['id'],
            $parsedBody['type'],
            $parsedBody['attributes'] ?? [],
            $relations
        );
    }

    private function validateResource($expectedType, $idType, $parsedBody)
    {
        Assert::lazy()
            ->that($parsedBody, 'request::body')
            ->keyExists('attributes')
            ->keyExists('id')
            ->keyExists('type')
            ->that($parsedBody['type'], 'request::body::type')
            ->eq($expectedType)
            ->verifyNow();

        if (array_key_exists('attributes', $parsedBody)) {
            Assert::lazy()
                ->that($parsedBody['attributes'], 'request::body::attributes')
                ->isArray()
                ->verifyNow();
        }

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

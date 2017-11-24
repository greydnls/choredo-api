<?php

namespace Choredo\Test\JsonApi;

use Assert\AssertionFailedException;
use Choredo\JsonApi\CompoundDocument;
use Choredo\JsonApi\JsonApiResource;
use Choredo\JsonApi\Relation;
use Choredo\JsonApi\Resource;
use Choredo\JsonApi\ResourceHydrator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ResourceHydratorTest extends TestCase
{
    public function testThrowsExceptionOnBadNewId()
    {
        $data = [
            'data' => [
                'id' => 'nope',
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $this->expectException(AssertionFailedException::class);

        ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_NEW,
            $data
        );
    }

    public function testThrowsExceptionOnBadUuidId()
    {
        $data = [
            'data' => [
                'id' => 'nope',
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $this->expectException(AssertionFailedException::class);

        ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_UUID,
            $data
        );
    }

    public function testThrowsExceptionOnMissingData()
    {
        $data = [
            'notData' => [
                'id' => 'new',
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $this->expectException(AssertionFailedException::class);

        ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_NEW,
            $data
        );
    }

    public function testThrowsExceptionOnMissingAttributes()
    {
        $data = [
            'notData' => [
                'id' => 'new',
                'type' => 'resources'
            ]
        ];

        $this->expectException(AssertionFailedException::class);

        ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_NEW,
            $data
        );
    }

    public function testThrowsExceptionOnBadType()
    {
        $data = [
            'notData' => [
                'id' => 'new',
                'type' => 'not-what-you-expected',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $this->expectException(AssertionFailedException::class);

        ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_NEW,
            $data
        );
    }

    public function testHydratesSimpleResource()
    {
        $data = [
            'data' => [
                'id' => 'new',
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $result = ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_NEW,
            $data
        );

        $this->assertInstanceOf(Resource::class, $result);
        $this->assertSame('new', $result->getId());
        $this->assertSame('resources', $result->getType());
        $this->assertTrue($result->hasAttribute('name'));
        $this->assertSame('test', $result->getAttribute('name'));
        $this->assertSame([], $result->getRelationships());
    }

    public function testHydratesResourceWithUuidIdType()
    {
        $id = Uuid::uuid4();
        $data = [
            'data' => [
                'id' => $id,
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ];

        $result = ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_UUID,
            $data
        );

        $this->assertInstanceOf(Resource::class, $result);
        $this->assertEquals($id, $result->getId());
        $this->assertSame('resources', $result->getType());
    }

    public function testHydratesResourceWithSingleEntityRelationship()
    {
        $id = Uuid::uuid4();
        $data = [
            'data' => [
                'id' => $id,
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ],
                'relationships' => [
                    'friend' => [
                        'data' => [
                            'id' => 'new',
                            'type' => 'friend'
                        ]
                    ]
                ]
            ]
        ];

        $result = ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_UUID,
            $data
        );

        $this->assertInstanceOf(Resource::class, $result);
        $this->assertTrue($result->hasRelationship('friend'));
        $this->assertInstanceOf(Relation::class, $result->getRelationship('friend'));
        $this->assertCount(1, $result->getRelationships());
    }

    public function testHydratesResourceWithEntityArrayRelationship()
    {
        $id = Uuid::uuid4();
        $data = [
            'data' => [
                'id' => $id,
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ],
                'relationships' => [
                    'friends' => [
                        'data' => [
                            [
                                'id' => 'new1',
                                'type' => 'friend'
                            ],
                            [
                                'id' => 'new2',
                                'type' => 'friend'
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $result = ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_UUID,
            $data
        );

        $this->assertInstanceOf(Resource::class, $result);
        $this->assertTrue($result->hasRelationship('friends'));
        $this->assertCount(2, $result->getRelationship('friends'));
        foreach ($result->getRelationship('friends') as $relationship){
            $this->assertInstanceOf(Relation::class, $relationship);
        }
        $this->assertCount(1, $result->getRelationships());
    }

    public function testHydratesRelationshipWithIncludedResource()
    {
        $id = Uuid::uuid4();
        $data = [
            'data' => [
                'id' => $id,
                'type' => 'resources',
                'attributes' => [
                    'name' => 'test'
                ],
                'relationships' => [
                    'friend' => [
                        'data' => [
                            'id' => 'new',
                            'type' => 'friend'
                        ]
                    ]
                ]
            ],
            'included' => [
                [
                    'id' => 'new',
                    'type' => 'friend',
                    'attributes' => [
                        'name' => 'Jim'
                    ]
                ]
            ]
        ];

        $result = ResourceHydrator::instance()->hydrate(
            'resources',
            JsonApiResource::TYPE_UUID,
            $data
        );

        $this->assertInstanceOf(Resource::class, $result);
        $this->assertTrue($result->hasRelationship('friend'));

        /** @var Relation $relation */
        $relation = $result->getRelationship('friend');

        $this->assertTrue($relation->isLoaded());
        $this->assertInstanceOf(Relation::class, $relation);
        $this->assertSame('friend', $relation->getType());
        $this->assertSame('Jim', $relation->getAttribute('name'));
    }
}

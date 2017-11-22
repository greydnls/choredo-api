<?php

namespace Choredo\Test\Hydrators;

use Assert\InvalidArgumentException;
use Assert\LazyAssertionException;
use Choredo\Entities;
use Choredo\Resource;
use Choredo\Middleware\FamilyHydrator;
use Choredo\Middleware\JsonApiResourceParser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use const Choredo\SHORT_DATA_FIELD_MAX_SIZE;

class FamilyHydratorTest extends TestCase
{
    protected $dataStub = [
        'name'            => 'test family',
        'paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
        'weekStartDay'    => 'sunday',
    ];

    public function testHydratorGeneratesIdWhenNotProvided()
    {
        (new FamilyHydrator())(
            $this->getRequest(),
            (new Response()),
            function (ServerRequest $request, Response $response) {
                /** @var Entities\Family $family */
                $family = $request->getAttribute('familyEntity');
                $this->assertInstanceOf(Entities\Family::class, $family);
                $this->assertInstanceOf(UuidInterface::class, $family->getId());

                return $response;
            }
        );
    }

    private function getRequest($id = JsonApiResourceParser::TYPE_NEW, $data = [])
    {
        $data = array_merge($this->dataStub, $data);

        return (new ServerRequest())
            ->withAttribute(
                'resource',
                new Resource($id, 'family', $data)
            );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value "this_is_not_a_uuid" is not a valid UUID.
     */
    public function testHydratorThrowsExceptionOnInvalidId()
    {
        (new FamilyHydrator())(
            $this->getRequest('this_is_not_a_uuid'),
            (new Response),
            function (ServerRequest $request, Response $response) {
                return $response;
            }
        );
    }

    /**
     * @param $completionThreshold
     * @param $exceptionMessage
     *
     * @dataProvider invalidPaymentStrategyCompletionThresholdCombinationsProvider
     */
    public function testHydratorHandlesValidationOfPerChorePaymentStrategy($completionThreshold, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);
        (new FamilyHydrator())(
            $this->getRequest(
                'new',
                [
                    'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHILD,
                    'completionThreshold' => $completionThreshold,
                ]
            ),
            (new Response),
            function (ServerRequest $request, Response $response) {
                return $response;
            }
        );
    }

    public function invalidPaymentStrategyCompletionThresholdCombinationsProvider(): array
    {
        return [
            [null, "Family::completionThreshold: Value \"<NULL>\" is null, but non null value was expected."],
            [
                Entities\Family::MIN_COMPLETION_THRESHOLD - 1,
                "Family::completionThreshold: Provided \"-1\" is neither greater than or equal to \"0\" " .
                "nor less than or equal to \"100\".",
            ],
            [
                Entities\Family::MAX_COMPLETION_THRESHOLD + 1,
                "Family::completionThreshold: Provided \"101\" is neither greater than or equal to \"0\" " .
                "nor less than or equal to \"100\"",
            ],
        ];
    }

    /**
     * @param $name
     * @param $exceptionMessage
     *
     * @dataProvider invalidNameProvider
     */
    public function testHydratorAppliesNameValidation($name, $exceptionMessage)
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage($exceptionMessage);
        (new FamilyHydrator())(
            $this->getRequest('new', ['name' => $name]),
            (new Response),
            function (ServerRequest $request, Response $response) {
                return $response;
            }
        );
    }

    public function invalidNameProvider(): array
    {
        return [
            [
                "",
                "Family::name: Value \"\" is too short, it should have at least 1 characters," .
                " but only has 0 characters.",
            ],
            [
                str_repeat('a', SHORT_DATA_FIELD_MAX_SIZE + 1),
                "Family::name: Value \"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa" .
                "aaaaaaaaaaaaaaaaaaaaaa...\" is too long, it should have no more than 255 characters, but has 256 " .
                "characters.",
            ],
        ];
    }

    public function testHydratorValidatesPaymentStrategyForPerChoreWithNullCompletionThreshold()
    {
        (new FamilyHydrator())(
            $this->getRequest('new', ['paymentStrategy' => Entities\Family::PAYMENT_STRATEGY_PER_CHORE]),
            (new Response),
            function (ServerRequest $request, Response $response) {
                /** @var Entities\Family $family */
                $family = $request->getAttribute('familyEntity');
                $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHORE, $family->getPaymentStrategy());
                $this->assertNull($family->getCompletionThreshold());

                return $response;
            }
        );
    }

    public function testHydratorValidatesPaymentStrategyPerChildWithCompletionThreshold()
    {
        (new FamilyHydrator())(
            $this->getRequest(
                'new',
                [
                    'paymentStrategy'     => Entities\Family::PAYMENT_STRATEGY_PER_CHORE,
                    'completionThreshold' => 100,
                ]
            ),
            (new Response),
            function (ServerRequest $request, Response $response) {
                /** @var Entities\Family $family */
                $family = $request->getAttribute('familyEntity');
                $this->assertEquals(Entities\Family::PAYMENT_STRATEGY_PER_CHORE, $family->getPaymentStrategy());
                $this->assertEquals(100, $family->getCompletionThreshold());

                return $response;
            }
        );
    }

    public function testHydratorThrowsExceptionWithInvalidStrategy()
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            "Family::paymentStrategy: Value \"this is not a real strategy\" is not an element of" .
            " the valid values: per_child, per_chore"
        );
        (new FamilyHydrator())(
            $this->getRequest('new', ['paymentStrategy' => "this is not a real strategy"]),
            (new Response),
            function (ServerRequest $request, Response $response) {
                return $response;
            }
        );
    }

    public function testHydratorThrowsExceptionOnInvalidWeekStartDay()
    {
        $this->expectException(LazyAssertionException::class);
        $this->expectExceptionMessage(
            "Family::weekStartDay: Value \"this is not a real day\" is not an element of the valid values: sunday," .
            " monday, tuesday, wednesday, thursday, friday, saturday"
        );
        (new FamilyHydrator())(
            $this->getRequest('new', ['weekStartDay' => 'this is not a real day']),
            (new Response),
            function (ServerRequest $request, Response $response) {
                return $response;
            }
        );
    }
}

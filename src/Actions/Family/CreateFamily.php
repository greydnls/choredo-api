<?php

namespace Choredo\Actions\Family;

use Assert\Assertion;
use Choredo\Hydrators;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Teapot\StatusCode;
use Zend\Diactoros\Response\JsonResponse;

class CreateFamily
{
    /**
     * @var Hydrators\Family
     */
    private $familyHydrator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateFamily constructor.
     * @param Hydrators\Family $familyHydrator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Hydrators\Family $familyHydrator, EntityManagerInterface $entityManager)
    {
        $this->familyHydrator = $familyHydrator;
        $this->entityManager = $entityManager;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $body = $request->getBody()->getContents();
        Assertion::isJsonString($body);

        $parsedBody = json_decode($body, true);

        $data = $parsedBody['attributes'] ?? [];
        $family = $this->familyHydrator->hydrate($data);

        $this->entityManager->persist($family);
        $this->entityManager->flush();

        return new JsonResponse("", StatusCode::CREATED);
    }
}

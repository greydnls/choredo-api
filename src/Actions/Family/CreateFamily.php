<?php

namespace Choredo\Actions\Family;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class CreateFamily
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateFamily constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $family = $request->getAttribute('familyEntity');

        $this->entityManager->persist($family);
        $this->entityManager->flush();

        return EmptyResponse::withHeaders([
            "location" => "/families/" . $family->getId()
        ]);
    }
}

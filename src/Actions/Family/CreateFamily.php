<?php

namespace Choredo\Actions\Family;

use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Choredo\Transformers\FamilyTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Teapot\StatusCode as Http;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class CreateFamily implements FractalAwareInterface, LoggerAwareInterface
{
    use CreatesFractalScope;
    use LoggerAwareTrait;

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
        /** @var \Choredo\Entities\Family $family */
        $family = $request->getAttribute(REQUEST_RESOURCE);

        $this->entityManager->persist($family);
        $this->entityManager->flush();

        $this->logger->info("New Family created", ["id" => $family->getId()->toString()]);

        $item = $this->outputItem($family, new FamilyTransformer(), 'families')->toArray();

        return (new JsonResponse($item, Http::CREATED))
            ->withHeader("location", "/families/" . $family->getId()->toString());
    }
}

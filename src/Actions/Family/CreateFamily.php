<?php

declare(strict_types=1);

namespace Choredo\Actions\Family;

use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\FamilyTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Teapot\StatusCode as Http;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class CreateFamily implements FractalAware, LoggerAwareInterface, EntityManagerAware
{
    use CreatesFractalScope;
    use LoggerAwareTrait;
    use HasEntityManager;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var \Choredo\Entities\Family $family */
        $family = $request->getAttribute(REQUEST_RESOURCE);

        $this->entityManager->persist($family);
        $this->entityManager->flush();

        $this->logger->info('New Family created', ['id' => $family->getId()->toString()]);

        $item = $this->outputItem($family, new FamilyTransformer(), 'families')->toArray();

        return (new JsonResponse($item, Http::CREATED))
            ->withHeader('location', '/families/' . $family->getId()->toString());
    }
}

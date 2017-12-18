<?php

declare(strict_types=1);

namespace Choredo\Actions\Child;

use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChildTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Teapot\StatusCode\Http;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class CreateChild implements FractalAware, LoggerAwareInterface, EntityManagerAware
{
    use CreatesFractalScope;
    use LoggerAwareTrait;
    use HasEntityManager;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): Response
    {
        /** @var \Choredo\Entities\Child $child */
        $child = $request->getAttribute(REQUEST_RESOURCE);

        $this->entityManager->persist($child);
        $this->entityManager->flush();

        $this->logger->info(
            'New Child created',
            ['id' => $child->getId()->toString(), 'familyId' => $child->getFamily()->getId()->toString()]
        );

        $item = $this->outputItem($child, new ChildTransformer(), 'children')->toArray();

        return (new JsonResponse($item, Http::CREATED))
            ->withHeader(
                'location',
                '/families/' . $child->getFamily()->getId()->toString() .
                '/children/' . $child->getId()->toString()
            );
    }
}

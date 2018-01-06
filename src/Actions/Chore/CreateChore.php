<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Entities\Chore;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChoreTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Teapot\StatusCode\Http;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class CreateChore implements FractalAware, LoggerAwareInterface, EntityManagerAware
{
    use CreatesFractalScope;
    use LoggerAwareTrait;
    use HasEntityManager;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        /** @var \Choredo\Entities\Chore $chore */
        $chore = $request->getAttribute(REQUEST_RESOURCE);

        $this->entityManager->persist($chore);
        $this->entityManager->flush();

        $choreId  = $chore->getId()->toString();
        $familyId = $chore->getFamily()->getId()->toString();

        $this->logger->info('New Chore created', ['id' => $choreId, 'familyId' => $familyId]);

        $resource = $this->outputItem($chore, new ChoreTransformer(), Chore::API_ENTITY_TYPE)->toArray();

        return (new JsonResponse($resource, Http::CREATED))
            ->withHeader('location', "/families/$familyId/chores/$choreId");
    }
}

<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Entities\Chore;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChoreTransformer;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

class GetChore implements FractalAware, EntityManagerAware
{
    use CreatesFractalScope;
    use HasEntityManager;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $params
     *
     * @throws \League\Route\Http\Exception\NotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        $repository = $this->entityManager->getRepository(Chore::class);
        $chore      = $repository->find($params['choreId']);

        if (!$chore) {
            throw new NotFoundException();
        }

        $resource = $this->outputItem($chore, new ChoreTransformer(), Chore::API_ENTITY_TYPE);

        return new JsonResponse($resource->toArray());
    }
}

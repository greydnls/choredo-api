<?php

declare(strict_types=1);

namespace Choredo\Actions\Child;

use Choredo\Entities\Child;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChildTransformer;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

class GetChild implements EntityManagerAware, FractalAware
{
    use HasEntityManager;
    use CreatesFractalScope;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $params
     *
     * @throws \League\Route\Http\Exception\NotFoundException
     *
     * @return \Zend\Diactoros\Response\JsonResponse
     */
    public function __invoke(Request $request, Response $response, array $params = [])
    {
        $repository = $this->entityManager->getRepository(Child::class);
        $child      = $repository->find($params['childId']);

        if (!$child) {
            throw new NotFoundException();
        }

        return new JsonResponse($this->outputItem($child, new ChildTransformer(), 'children')->toArray());
    }
}

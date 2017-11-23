<?php

namespace Choredo\Actions\Family;

use Choredo\Entities\Family;
use Choredo\EntityManagerAwareInterface;
use Choredo\HasEntityManager;
use const Choredo\REQUEST_FAMILY;
use League\Route\Http\Exception\NotFoundException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Teapot\StatusCode;
use Zend\Diactoros\Response\EmptyResponse;

class DeleteFamily implements LoggerAwareInterface, EntityManagerAwareInterface
{
    use LoggerAwareTrait;
    use HasEntityManager;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        /** @var Family $family */
        $family = $request->getAttribute(REQUEST_FAMILY);

        if (!$family) {
            throw new NotFoundException();
        }

        $id = $family->getId();

        $this->entityManager->remove($family);
        $this->entityManager->flush();

        $this->logger->info("Deleted Family", ["id" => $id]);

        return new EmptyResponse(StatusCode::NO_CONTENT);
    }


}
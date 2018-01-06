<?php

declare(strict_types=1);

namespace Choredo\Actions\Chore;

use Choredo\Entities\Chore;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers\ChoreTransformer;
use League\Route\Http\Exception\ConflictException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class UpdateChore implements FractalAware, EntityManagerAware, LoggerAwareInterface
{
    use CreatesFractalScope;
    use HasEntityManager;
    use LoggerAwareTrait;

    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        /** @var Chore $newChore */
        /** @var Chore $oldChore */
        $repository = $this->entityManager->getRepository(Chore::class);
        $newChore   = $request->getAttribute(REQUEST_RESOURCE);
        $oldChore   = $repository->find($params['choreId']);

        if (!$newChore->getId()->equals($oldChore->getId())) {
            throw new ConflictException('Chore ID mismatch');
        }

        if (!$newChore->getFamily()->getId()->equals($oldChore->getFamily()->getId())) {
            throw new ConflictException('Family ID mismatch');
        }

        if (!$oldChore) {
            throw new NotFoundException();
        }

        Chore::validate(
            $oldChore->getId(),
            $oldChore->getFamily(),
            $newChore->getName(),
            $newChore->getSchedule(),
            $newChore->getDescription(),
            $newChore->getValue()
        );

        $oldChore
            ->setName($newChore->getName())
            ->setSchedule($newChore->getSchedule())
            ->setDescription($newChore->getDescription())
            ->setValue($newChore->getValue())
        ;
        $this->entityManager->persist($oldChore);
        $this->entityManager->flush();

        $this->logger->info('Chore updated', ['id' => $oldChore->getId(), 'family' => $oldChore->getFamily()->getId()]);

        $resource = $this->outputItem($oldChore, new ChoreTransformer(), Chore::API_ENTITY_TYPE);

        return new JsonResponse($resource->toArray());
    }
}

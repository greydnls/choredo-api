<?php

declare(strict_types=1);

namespace Choredo\Actions;

use Choredo\Entities\Account;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAwareInterface;
use Choredo\Transformers\AccountTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Teapot\StatusCode\Http;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_RESOURCE;

class Register implements EntityManagerAware, FractalAwareInterface, LoggerAwareInterface
{
    use CreatesFractalScope;
    use HasEntityManager;
    use LoggerAwareTrait;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var Account $account */
        $account = $request->getAttribute(REQUEST_RESOURCE);

        $this->entityManager->persist($account);
        $this->entityManager->persist($account->getFamily());
        $this->entityManager->flush();

        $this->logger->info('New Account created', ['id' => $account->getId()->toString()]);

        $item = $this->outputItem($account, new AccountTransformer(), 'accounts')->toArray();

        return (new JsonResponse($item, Http::CREATED))
            ->withHeader('location', '/accounts/' . $account->getId()->toString());
    }
}

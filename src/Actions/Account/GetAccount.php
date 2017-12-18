<?php

declare(strict_types=1);

namespace Choredo\Actions\Account;

use Choredo\Entities\Account;
use Choredo\EntityManagerAware;
use Choredo\HasEntityManager;
use Choredo\Output\CreatesFractalScope;
use Choredo\Output\FractalAware;
use Choredo\Transformers;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use const Choredo\REQUEST_FAMILY;

class GetAccount implements FractalAware, EntityManagerAware
{
    use CreatesFractalScope;
    use HasEntityManager;

    public function __invoke(Request $request, Response $response, array $params = []): Response
    {
        $accountId = $params['accountId'];
        $family    = $request->getAttribute(REQUEST_FAMILY);

        $repository = $this->entityManager->getRepository(Account::class);
        $account    = $repository->findOneBy(['id' => $accountId, 'family' => $family->getId()]);

        if (!$account) {
            throw new NotFoundException();
        }

        return new JsonResponse(
            $this->outputItem($account, new Transformers\AccountTransformer(), 'accounts')->toArray()
        );
    }
}

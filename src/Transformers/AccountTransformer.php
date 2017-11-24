<?php


namespace Choredo\Transformers;


use Choredo\Entities\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    public function transform(Account $account)
    {
        return [
            'id' => $account->getId(),
            'firstName' => $account->getFirstName(),
            'lastName' => $account->getLastName(),
            'email' => $account->getEmailAddress(),
            'avatarUri' => $account->getAvatarUri()
        ];
    }
}
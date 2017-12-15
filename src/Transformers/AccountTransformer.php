<?php


namespace Choredo\Transformers;

use Choredo\Entities\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['family'];

    private $familyTransformer;

    public function __construct(FamilyTransformer $familyTransformer = null)
    {
        $this->familyTransformer = $familyTransformer ?? new FamilyTransformer();
    }

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

    public function includeFamily(Account $account)
    {
        return $this->item($account->getFamily(), $this->familyTransformer, 'families');
    }
}

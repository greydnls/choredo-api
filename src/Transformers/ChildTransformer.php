<?php

namespace Choredo\Transformers;

use Choredo\Entities\Child;
use League\Fractal\TransformerAbstract;

class ChildTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['family'];

    private $familyTransformer;

    public function __construct(FamilyTransformer $familyTransformer = null)
    {
        $this->familyTransformer = $familyTransformer ?? new FamilyTransformer();
    }

    public function transform(Child $child)
    {
        return [
            'id'          => $child->getId(),
            'name'        => $child->getName(),
            'accessCode'  => $child->getAccessCode(),
            'avatarUri'   => $child->getAvatarUri(),
            'createdDate' => $child->getCreatedDate(),
            'updatedDate' => $child->getUpdateDate(),
        ];
    }

    public function includeFamily(Child $child)
    {
        return $this->item($child->getFamily(), $this->familyTransformer, 'families');
    }
}

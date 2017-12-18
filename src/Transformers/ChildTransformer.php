<?php

declare(strict_types=1);

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
        $entity = [
            'id'          => $child->getId(),
            'name'        => $child->getName(),
            'color'       => $child->getColor(),
            'accessCode'  => $child->getAccessCode(),
            'avatarUri'   => $child->getAvatarUri(),
            'createdDate' => $child->getCreatedDate(),
            'updatedDate' => $child->getUpdateDate(),
        ];

        if ($child->getCreatedDate()) {
            $entity['createdDate'] = $child->getCreatedDate()->format(\DateTime::ATOM);
        }

        if ($child->getUpdateDate()) {
            $entity['updatedDate'] = $child->getUpdateDate()->format(\DateTime::ATOM);
        }

        return $entity;
    }

    public function includeFamily(Child $child)
    {
        return $this->item($child->getFamily(), $this->familyTransformer, 'families');
    }
}

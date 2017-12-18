<?php

declare(strict_types=1);

namespace Choredo\Entities\Filters;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class FamilyFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($targetEntity->reflClass->hasProperty('family')) {
            return $targetTableAlias . '.family_id = ' . $this->getParameter('familyId');
        }

        return '';
    }
}

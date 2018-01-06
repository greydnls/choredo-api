<?php

declare(strict_types=1);

namespace Choredo\Transformers;

use Choredo\Entities\Chore;
use League\Fractal\TransformerAbstract;

class ChoreTransformer extends TransformerAbstract
{
    public function transform(Chore $chore)
    {
        return [
            'id'          => $chore->getId(),
            'name'        => $chore->getName(),
            'schedule'    => $chore->getSchedule(),
            'frequency'   => $this->getChoreFrequency($chore),
            'description' => $chore->getDescription(),
            'value'       => $chore->getValue(),
            'createdDate' => $chore->getCreatedDate()->format(\DateTime::ATOM),
            'updatedDate' => $chore->getUpdateDate()->format(\DateTime::ATOM),
        ];
    }

    private function getChoreFrequency(Chore $chore): string
    {
        if (count(array_keys($chore->getSchedule())) === 7) {
            return 'daily';
        }

        if (count(array_keys($chore->getSchedule())) === 1) {
            return 'weekly';
        }

        return 'custom';
    }
}

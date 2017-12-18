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
            'description' => $chore->getDescription(),
            'value'       => $chore->getValue(),
        ];
    }
}

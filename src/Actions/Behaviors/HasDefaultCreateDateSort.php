<?php

declare(strict_types=1);

namespace Choredo\Actions\Behaviors;

use Choredo\Sort;

trait HasDefaultCreateDateSort
{
    /**
     * @return array $sort {
     *
     * @var string $field
     * @var string $direction
     *             }
     */
    public static function getDefaultSort(): array
    {
        return [
            (new Sort('createdDate', Sort::DIRECTION_ASCENDING)),
        ];
    }
}

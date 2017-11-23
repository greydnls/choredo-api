<?php

namespace Choredo\Actions\Behaviors;

use Choredo\Sort;

trait HasDefaultCreateDateSort
{
    /**
     * @return array $sort {
     * @type string $field
     * @type string $direction
     * }
     */
    public static function getDefaultSort(): array
    {
        return [
            (new Sort('createdDate', Sort::DIRECTION_ASCENDING))
        ];
    }
}

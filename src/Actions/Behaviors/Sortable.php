<?php

namespace Choredo\Actions\Behaviors;

interface Sortable
{
    /**
     * @return string[]
     */
    public static function getSortableFields(): array;

    /**
     * @return array $sort {
     * @type string $field
     * @type string $direction
     * }
     */
    public static function getDefaultSort(): array;
}

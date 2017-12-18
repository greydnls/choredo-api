<?php

declare(strict_types=1);

namespace Choredo\Actions\Behaviors;

interface Sortable
{
    /**
     * @return string[]
     */
    public static function getSortableFields(): array;

    /**
     * @return array $sort {
     *
     * @var string $field
     * @var string $direction
     *             }
     */
    public static function getDefaultSort(): array;
}

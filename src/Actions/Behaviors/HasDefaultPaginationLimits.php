<?php

namespace Choredo\Actions\Behaviors;

trait HasDefaultPaginationLimits
{
    /**
     * Return the default limit (resources per page)
     *
     * @return int
     */
    public static function getDefaultLimit(): int
    {
        return Pageable::DEFAULT_LIMIT;
    }

    /**
     * Return the maximum page size
     *
     * @return int
     */
    public static function getMaxLimit(): int
    {
        return Pageable::DEFAULT_MAX_LIMIT;
    }
}
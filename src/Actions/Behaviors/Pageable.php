<?php

declare(strict_types=1);

namespace Choredo\Actions\Behaviors;

interface Pageable
{
    const DEFAULT_LIMIT     = 10;
    const DEFAULT_MAX_LIMIT = 100;
    const DEFAULT_OFFSET    = 0;

    /**
     * Return the default limit (resources per page).
     *
     * @return int
     */
    public static function getDefaultLimit(): int;

    /**
     * Return the maximum page size.
     *
     * @return int
     */
    public static function getMaxLimit(): int;
}

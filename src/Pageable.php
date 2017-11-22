<?php

namespace Choredo;

interface Pageable
{
    const DEFAULT_LIMIT = 10;
    const DEFAULT_MAX_LIMIT = 100;
    const DEFAULT_OFFSET = 0;

    public static function getDefaultLimit(): int;
    public static function getMaxLimit(): int;
}

<?php

declare(strict_types=1);

namespace Choredo\Actions\Behaviors;

trait HasNoFilterTransforms
{
    /**
     * @return array
     */
    public static function getFilterTransforms(): array
    {
        return [];
    }
}

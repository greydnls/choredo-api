<?php

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

<?php

namespace Choredo\Actions\Behaviors;

interface Filterable
{
    /**
     * Return an array of fields that can be filtered via the API
     *
     * @return string[]
     */
    public static function getFilterableFields(): array;

    /**
     * @return array {
     * @type string $field Name of the field which requires a transform
     * @type callable $callable A callable transform function `function ($value) {}`
     * }
     */
    public static function getFilterTransforms(): array;
}

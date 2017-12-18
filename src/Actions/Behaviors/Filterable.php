<?php

declare(strict_types=1);

namespace Choredo\Actions\Behaviors;

interface Filterable
{
    /**
     * Return an array of fields that can be filtered via the API.
     *
     * @return string[]
     */
    public static function getFilterableFields(): array;

    /**
     * @return array {
     *
     * @var string   $field Name of the field which requires a transform
     * @var callable $callable A callable transform function `function ($value) {}`
     *               }
     */
    public static function getFilterTransforms(): array;
}

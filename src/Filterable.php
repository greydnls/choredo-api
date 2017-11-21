<?php

namespace Choredo;

interface Filterable
{
    /**
     * Return an array of fields that can be filtered via the API
     *
     * @return array
     */
    public static function getFilterableFields(): array;
}

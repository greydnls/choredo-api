<?php

namespace Choredo;

interface Sortable
{
    public static function getSortableFields(): array;
    public static function getDefaultSort(): array;
}

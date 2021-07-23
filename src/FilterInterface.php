<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Illuminate\Database\Query\Builder;

interface FilterInterface
{
    public static function neededKeys(): array;

    public static function isApplicable(... $args): bool;

    public function apply(Builder &$queryBuilder, ... $args): void;
}

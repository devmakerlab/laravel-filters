<?php

declare(strict_types=1);

namespace Tests\Example\Filters;

use Illuminate\Database\Query\Builder;
use DevMakerLab\LaravelFilters\FilterInterface;

class OldPeopleFilter implements FilterInterface
{
    public static function neededKeys(): array
    {
        return ['age'];
    }

    public static function isApplicable(...$args): bool
    {
        return true;
    }

    public function apply(Builder &$queryBuilder, ...$args): void
    {
        $age = $args[0][self::neededKeys()[0]];

        $queryBuilder->where('age', '>=', $age);
    }
}

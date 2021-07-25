<?php

declare(strict_types=1);

namespace Tests\Example\Filters;

use Illuminate\Database\Query\Builder;
use DevMakerLab\LaravelFilters\AbstractFilter;

class OldPeopleFilter extends AbstractFilter
{
    public int $age;

    public function apply(Builder $queryBuilder): void
    {
        $queryBuilder->where('age', '>=', $this->age);
    }
}

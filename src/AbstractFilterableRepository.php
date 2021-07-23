<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Illuminate\Database\Query\Builder;

abstract class AbstractFilterableRepository
{
    protected array $filters;

    protected ?int $limit = null;

    /**
     * @throws FilterClassNotFound
     * @throws IncorrectFilterException
     */
    public function addFilter(string $filter): self
    {
        if (! class_exists($filter)) {
            throw new FilterClassNotFound();
        }

        if (! is_subclass_of($filter, FilterInterface::class)) {
            throw new IncorrectFilterException($filter);
        }

        $this->filters[] = $filter;

        return $this;
    }

    public function resetFilters(): self
    {
        $this->filters = [];

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function resetLimit(): self
    {
        $this->limit = null;

        return $this;
    }

    public function applyFilters(Builder &$builder, array $args): self
    {
        foreach ($this->filters as $filter) {
            $neededKeys = $filter::neededKeys();
            $neededArgs = $this->extractNeededArgs($neededKeys, $args);

            if ($filter::isApplicable($neededArgs)) {
                (new $filter)->apply($builder, $neededArgs);
            }
        }

        if ($this->limit) {
            $builder->limit($this->limit);
        }

        $this->resetFilters();
        $this->resetLimit();

        return $this;
    }

    private function extractNeededArgs(array $neededKeys, array $args): array
    {
        return array_intersect_key($args, array_flip($neededKeys));
    }
}

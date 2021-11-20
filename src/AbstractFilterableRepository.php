<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\DatabaseManager;

abstract class AbstractFilterableRepository
{
    protected string $table;
    protected array $attributes = [];
    protected ?int $limit = null;
    protected array $filters = [];

    protected DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @throws FilterClassNotFound
     * @throws IncorrectFilterException
     */
    public function addFilter(string $filter): self
    {
        if (! class_exists($filter)) {
            throw new FilterClassNotFound();
        }

        if (! is_subclass_of($filter, AbstractFilter::class)) {
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

    public function get(array $args = []): iterable
    {
        $queryBuilder = $this->databaseManager->table($this->table)
            ->select($this->attributes);

        $this->applyFilters($queryBuilder, $args);

        $result = $queryBuilder->get();

        if (property_exists($this, 'shouldTransform') && $this->shouldTransform === true) {
            if (method_exists($this, 'transform')) {
                return $this->transform($result);
            } else {
                throw new TransformMethodNotImplementedException(static::class);
            }
        }

        return $result;
    }

    public function applyFilters(Builder &$builder, array $args): self
    {
        foreach ($this->filters as $filter) {
            $neededArgs = $this->extractNeededArgs($filter, $args);

            if ($filter::isApplicable($neededArgs)) {
                $filterInstance = new $filter($neededArgs);
                $filterInstance->apply($builder);
            }
        }

        if ($this->limit) {
            $builder->limit($this->limit);
        }

        $this->resetFilters();
        $this->resetLimit();

        return $this;
    }

    private function extractNeededArgs(string $class, array $args): array
    {
        return array_intersect_key($args, array_flip(array_keys(get_class_vars($class))));
    }
}

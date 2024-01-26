<?php

declare(strict_types=1);

use Tests\TestCase;
use DevMakerLab\LaravelFilters\AbstractFilter;
use DevMakerLab\LaravelFilters\FilterClassNotFound;
use DevMakerLab\LaravelFilters\IncorrectFilterException;
use DevMakerLab\LaravelFilters\AbstractFilterableRepository;

class AbstractFilterableRepositoryTest extends TestCase
{
    public function testExceptionThrownWhenAddingNonExistingFilter(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
        };

        $this->expectException(FilterClassNotFound::class);
        $abstractRepository->addFilter(FooFilter::class);
    }

    public function testExceptionThrownWhenAddingFilterWhichIsNotExtendingAbstractFilter(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
        };
        $filter = new class {
        };

        $this->expectException(IncorrectFilterException::class);
        $abstractRepository->addFilter($filter::class);
    }

    public function testCanAddFilter(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
            public function getFilters(): array
            {
                return $this->filters;
            }
        };

        $filter = new class extends AbstractFilter {
        };

        $abstractRepository->addFilter($filter::class);

        $this->assertCount(1, $abstractRepository->getFilters());
        $this->assertSame($filter::class, $abstractRepository->getFilters()[0]);
    }

    public function testCanResetFilters(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
            public function getFilters(): array
            {
                return $this->filters;
            }
        };

        $filter = new class extends AbstractFilter {
        };

        $abstractRepository->addFilter($filter::class);
        $this->assertCount(1, $abstractRepository->getFilters());

        $abstractRepository->resetFilters();
        $this->assertCount(0, $abstractRepository->getFilters());
    }

    public function testCanSpecifyLimit(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
            public function getLimit(): ?int
            {
                return $this->limit;
            }
        };

        $this->assertNull($abstractRepository->getLimit());

        $abstractRepository->limit(10);
        $this->assertSame(10, $abstractRepository->getLimit());
    }

    public function testCanResetLimit(): void
    {
        $abstractRepository = new class extends AbstractFilterableRepository {
            public function getLimit(): ?int
            {
                return $this->limit;
            }
        };

        $this->assertNull($abstractRepository->getLimit());

        $abstractRepository->limit(10);
        $this->assertSame(10, $abstractRepository->getLimit());

        $abstractRepository->resetLimit();
        $this->assertNull($abstractRepository->getLimit());
    }
}

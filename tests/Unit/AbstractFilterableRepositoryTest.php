<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Collection;
use DevMakerLab\LaravelFilters\AbstractFilter;
use DevMakerLab\LaravelFilters\FilterClassNotFound;
use DevMakerLab\LaravelFilters\IncorrectFilterException;
use DevMakerLab\LaravelFilters\TransformMethodNotImplementedException;

class AbstractFilterableRepositoryTest extends TestCase
{
    public function testExceptionThrownWhenAskedForTransformWithoutDefiningTransformMethod(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $this->expectException(TransformMethodNotImplementedException::class);
        $abstractRepository->get();
    }

    public function testCanGet(): void
    {
        $abstractRepository = $this->getAbstractRepository(false);

        $result = $abstractRepository->get();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame([
            'first item',
            'second item',
        ], $result->toArray());
    }

    public function testExceptionThrownWhenAddingNonExistingFilter(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $this->expectException(FilterClassNotFound::class);
        $abstractRepository->addFilter(FooFilter::class);
    }

    public function testExceptionThrownWhenAddingFilterWhichIsNotExtendingAbstractFilter(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $filter = new class {
        };

        $this->expectException(IncorrectFilterException::class);
        $abstractRepository->addFilter(get_class($filter));
    }

    public function testCanAddFilter(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $filter = new class extends AbstractFilter {
        };

        $abstractRepository->addFilter(get_class($filter));

        $this->assertCount(1, $abstractRepository->getFilters());
        $this->assertSame(get_class($filter), $abstractRepository->getFilters()[0]);
    }

    public function testCanResetFilters(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $filter = new class extends AbstractFilter {
        };

        $abstractRepository->addFilter(get_class($filter));
        $this->assertCount(1, $abstractRepository->getFilters());

        $abstractRepository->resetFilters();
        $this->assertCount(0, $abstractRepository->getFilters());
    }

    public function testCanSpecifyLimit(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $this->assertNull($abstractRepository->getLimit());

        $abstractRepository->limit(10);
        $this->assertSame(10, $abstractRepository->getLimit());
    }

    public function testCanResetLimit(): void
    {
        $abstractRepository = $this->getAbstractRepository();

        $this->assertNull($abstractRepository->getLimit());

        $abstractRepository->limit(10);
        $this->assertSame(10, $abstractRepository->getLimit());

        $abstractRepository->resetLimit();
        $this->assertNull($abstractRepository->getLimit());
    }
}

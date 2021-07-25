<?php

declare(strict_types=1);

use Tests\TestCase;
use DevMakerLab\LaravelFilters\AbstractFilter;
use DevMakerLab\LaravelFilters\NoApplyFilterRuleException;

class AbstractFilterTest extends TestCase
{
    public function testExceptionThrownWhenNoApplyFunctionOverride(): void
    {
        $filter = new class extends AbstractFilter {
        };

        $this->expectException(NoApplyFilterRuleException::class);

        $filter->apply($this->app['db']->query());
    }

    public function testCanFeedPropertiesByConstruct(): void
    {
        $filter = new class extends AbstractFilter {
            public string $foo;
        };

        $filterInstance = new $filter(['foo' => 'hey', 'bar' => 'nope']);

        $this->assertSame('hey', $filterInstance->foo);
        $this->expectException(Exception::class);
        $this->assertNull($filterInstance->bar);
    }
}

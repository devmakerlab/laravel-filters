<?php

declare(strict_types=1);

use Tests\TestCase;
use Tests\Example\Filters\OldPeopleFilter;
use Tests\Example\Entities\PeopleEntityList;

class AbstractFilterableRepositoryTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** */
    public function testCan_get_old_people(): void
    {
        $peopleRepository = $this->instantiatePeopleRepository();

        $people = $peopleRepository
            ->addFilter(OldPeopleFilter::class)
            ->get(['age' => 60]);

        $this->assertInstanceOf(PeopleEntityList::class, $people);
    }
}

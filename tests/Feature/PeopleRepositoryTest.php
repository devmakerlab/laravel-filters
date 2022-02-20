<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Example\Filters\OldPeopleFilter;
use Tests\Example\Entities\PeopleEntityList;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PeopleRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** */
    public function testCan_get_old_people(): void
    {
        $peopleRepository = $this->instantiatePeopleRepository();

        $this->createPeople([
            [
                'firstname' => 'Snoop',
                'lastname' => 'Dogg',
                'gender' => 'male',
                'age' => 49,
            ],
            [
                'firstname' => 'Danielle',
                'lastname' => 'Studio',
                'gender' => 'female',
                'age' => 67,
            ],
        ]);

        $people = $peopleRepository
            ->addFilter(OldPeopleFilter::class)
            ->limit(1)
            ->get(['age' => 60]);

        $this->assertInstanceOf(PeopleEntityList::class, $people);
        $this->assertCount(1, $people);
        $this->assertInstanceOf(\Tests\Example\Entities\PeopleEntity::class, $people[0]);
    }
}

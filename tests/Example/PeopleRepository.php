<?php

declare(strict_types=1);

namespace Tests\Example;

use Tests\Example\Entities\PeopleEntity;
use Tests\Example\Entities\PeopleEntityList;
use DevMakerLab\LaravelFilters\AbstractFilterableRepository;

class PeopleRepository extends AbstractFilterableRepository
{
    private \Illuminate\Database\DatabaseManager $databaseManager;

    public function __construct(\Illuminate\Database\DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function get(array $args): PeopleEntityList
    {
        $queryBuilder = $this->databaseManager->table('people')
            ->select(['firstname', 'lastname', 'age', 'gender']);

        $this->applyFilters($queryBuilder, $args);

        $people = $queryBuilder->get();

        return $this->transform($people);
    }

    public function transform(\Illuminate\Support\Collection $people): PeopleEntityList
    {
        $people->transform(function ($person) {
            return new PeopleEntity([
                'name' => sprintf('%s %s', $person->lastname, $person->firstname),
                'age' => $person->age,
                'gender' => $person->gender,
            ]);
        });

        return new PeopleEntityList($people->toArray());
    }
}

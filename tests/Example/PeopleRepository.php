<?php

declare(strict_types=1);

namespace Tests\Example;

use Illuminate\Support\Collection;
use Tests\Example\Entities\PeopleEntity;
use Illuminate\Database\DatabaseManager;
use Tests\Example\Entities\PeopleEntityList;
use DevMakerLab\LaravelFilters\AbstractFilterableRepository;

class PeopleRepository extends AbstractFilterableRepository
{
    protected string $table = 'people';
    protected array $attributes = ['firstname', 'lastname', 'age', 'gender'];
    protected bool $shouldTransform = true;

    public function __construct(DatabaseManager $databaseManager)
    {
        parent::__construct($databaseManager);
    }

    public function transform(Collection $people): PeopleEntityList
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

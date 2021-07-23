<?php

declare(strict_types=1);

namespace Tests\Example\Entities;

use DevMakerLab\EntityList;

class PeopleEntityList extends EntityList
{
    protected string $expectedType = PeopleEntity::class;
}

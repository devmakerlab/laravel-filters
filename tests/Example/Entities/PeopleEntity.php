<?php

declare(strict_types=1);

namespace Tests\Example\Entities;

use DevMakerLab\Entity;

class PeopleEntity extends Entity
{
    public string $name;
    public int $age;
    public string $gender;
}

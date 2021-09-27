<p align="center">
<img src="https://user-images.githubusercontent.com/51158042/126819815-69be48fa-40a8-49e4-81ca-d3ebc1d3d521.png">
</p>

<p align="center">
<a href="https://scrutinizer-ci.com/g/devmakerlab/laravel-filters/"><img src="https://scrutinizer-ci.com/g/devmakerlab/laravel-filters/badges/build.png?b=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/devmakerlab/laravel-filters/?branch=master"><img src="https://scrutinizer-ci.com/g/devmakerlab/laravel-filters/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/devmakerlab/laravel-filters"><img src="https://img.shields.io/packagist/v/devmakerlab/laravel-filters.svg" alt="Latest Version on Packagist"></a>
<a href='https://packagist.org/packages/devmakerlab/laravel-filters'><img src="https://img.shields.io/packagist/dt/devmakerlab/laravel-filters.svg?style=flat-square"/></a> 
</p>


# DevMakerLab/Laravel-Filters

Need some filters? This package is based on the Repository Design Pattern to let you create specific queries easily.

* [Installation](#installation)
* [Usage](#usage)
* [Example](#example)

## Installation
<small>⚠️ Requires >= PHP 7.4 ⚠️</small>
```shell
composer require devmakerlab/laravel-filters
```

## Usage

This package offers an abstract class `AbstractFilterableRepository` which needs to be extended to implement the features of this package.


PeopleService.php
```php
<?php
    ...
    $peopleRepository = new PeopleRepository($databaseManager);
    
    $people = $peopleRepository
            ->addFilter(OldPeopleFilter::class)
            ->get(['age' => 60]);
```

OldPeopleFilter.php
```php
<?php

declare(strict_types=1);

use Illuminate\Database\Query\Builder;
use DevMakerLab\LaravelFilters\AbstractFilter;

class OldPeopleFilter extends AbstractFilter
{
    public int $age;

    public function apply(Builder $queryBuilder): void
    {
        $queryBuilder->where('age', '>=', $this->age);
    }
}
```

PeopleRepository.php
```php
<?php

declare(strict_types=1);

use DevMakerLab\LaravelFilters\AbstractFilterableRepository;

class PeopleRepository extends AbstractFilterableRepository
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function get(array $args): array
    {
        $queryBuilder = $this->databaseManager->table('people')
            ->select(['firstname', 'lastname', 'age', 'gender']);

        $this->applyFilters($queryBuilder, $args);

        $people = $queryBuilder->get();

        return $this->transform($people);
    }

    public function transform(Collection $people): array
    {
        $people->transform(function ($person) {
            return [
                'name' => sprintf('%s %s', $person->lastname, $person->firstname),
                'age' => $person->age,
                'gender' => $person->gender,
            ];
        });

        return $people->toArray();
    }
}
```

## Example

[Usage Example](https://github.com/devmakerlab/laravel-filters/tree/master/tests/Example) of DevMakerLab/Laravel-Filters package.

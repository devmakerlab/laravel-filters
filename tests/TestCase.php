<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Collection;
use Tests\Example\PeopleRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use DevMakerLab\LaravelFilters\AbstractFilterableRepository;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        parent::afterApplicationCreated(function (): void {
            Schema::create('people', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('firstname');
                $table->string('lastname');
                $table->integer('age');
                $table->string('gender');
            });
        });
    }

    public function instantiatePeopleRepository(): PeopleRepository
    {
        return new PeopleRepository($this->app['db']);
    }

    public function createPeople(array $peoples): void
    {
        $this->app['db']
            ->table('people')
            ->insert($peoples);
    }

    public function getAbstractRepository(bool $shouldTransform = true): AbstractFilterableRepository
    {
        $db = $this->partialMock(DatabaseManager::class);
        $queryBuilder = $this->partialMock(Builder::class);
        $db->shouldReceive('table')
            ->andReturn($queryBuilder);
        $db->shouldReceive('select')
            ->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('get')
            ->andReturn(new Collection([
                0 => 'first item',
                1 => 'second item',
            ]));

        return new class($db, $shouldTransform) extends AbstractFilterableRepository {
            protected string $table = 'table';
            protected array $attributes = [];
            protected bool $shouldTransform;

            public function __construct(DatabaseManager $databaseManager, bool $shouldTransform)
            {
                parent::__construct($databaseManager);
                $this->shouldTransform = $shouldTransform;
            }

            public function getFilters(): array
            {
                return $this->filters;
            }

            public function getLimit(): ?int
            {
                return $this->limit;
            }
        };
    }
}

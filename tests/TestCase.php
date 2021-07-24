<?php

declare(strict_types=1);

namespace Tests;

use Tests\Example\PeopleRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

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
}

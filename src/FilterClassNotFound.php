<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Exception;

class FilterClassNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}

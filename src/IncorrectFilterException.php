<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Exception;

class IncorrectFilterException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('Class `%s` does not extends FilterInterface.', $class));
    }
}

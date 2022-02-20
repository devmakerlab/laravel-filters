<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Exception;

class TransformMethodNotImplementedException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('Class `%s` does not implement transform function although property `$shouldTransform` is set to true.', $class));
    }
}

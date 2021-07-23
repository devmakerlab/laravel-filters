<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Exception;

class NoApplyFilterRuleException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('Filter class `%s` has no apply function.', $class));
    }
}

<?php

declare(strict_types=1);

namespace DevMakerLab\LaravelFilters;

use Illuminate\Database\Query\Builder;

abstract class AbstractFilter
{
    public function __construct(array $args = [])
    {
        $this->setAttributes($args);
    }

    protected function setAttributes(array $attributes): void
    {
        foreach (get_class_vars(static::class) as $key => $value) {
            if (isset($attributes[$key])) {
                $this->$key = $attributes[$key];
            }
        }
    }

    public static function isApplicable(... $args): bool
    {
        return true;
    }

    /**
     * @throws NoApplyFilterRuleException
     */
    public function apply(Builder &$queryBuilder): void
    {
        throw new NoApplyFilterRuleException(self::class);
    }
}

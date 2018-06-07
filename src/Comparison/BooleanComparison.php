<?php

namespace PhpAbac\Comparison;

class BooleanComparison extends AbstractComparison
{
    public function boolAnd(bool $expected, bool $value): bool
    {
        return $expected && $value;
    }

    public function boolOr($expected, $value): bool
    {
        return $expected || $value;
    }
    
    public function isNull($expected, $value): bool
    {
        return $value === null;
    }
    
    public function isNotNull($expected, $value): bool
    {
        return $value !== null;
    }
}

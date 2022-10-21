<?php

namespace app\utils;

class Arrays
{
    static function select(array $arr, array $keys, bool $isInclude = true): array
    {
        return array_filter(
            $arr,
            function ($key) use ($keys, $isInclude) {
                $isContains = in_array($key, $keys);
                return $isInclude ? $isContains : !$isContains;
            },
            ARRAY_FILTER_USE_KEY);
    }
}
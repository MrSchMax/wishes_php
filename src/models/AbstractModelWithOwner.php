<?php

namespace app\models;

abstract class AbstractModelWithOwner extends AbstractModel
{
    protected const OWNER_KEY = 'userId';

    public static function findByOwner($owner): array
    {
        return static::findByValue(static::OWNER_KEY, $owner);
    }

    public static function getCurrentForOwner($id, $ownerId): ?object
    {
        $item = static::findById($id);
        return $item && $ownerId == $item->{static::OWNER_KEY}
            ? $item
            : null
            ;
    }

}

// 1. get list by id
// 2. if user not owner -> end
//
<?php

namespace app;

abstract class AbstractModelWithOwner extends AbstractModel
{
    protected const OWNER_KEY = 'userId';

    public static function findByOwner(string $owner): array
    {
        return self::findByValue(self::OWNER_KEY, $owner);
    }

    public static function getCurrentForOwner($id, $ownerId): ?object
    {
        $item = self::findById($id);
        return $item && $ownerId == $item->{self::OWNER_KEY}
            ? $item
            : null
            ;
    }

}
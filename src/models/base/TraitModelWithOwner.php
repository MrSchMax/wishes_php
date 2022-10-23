<?php
namespace app\models\base;

// Требует наличие константы OWNER_KEY

trait TraitModelWithOwner
{

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
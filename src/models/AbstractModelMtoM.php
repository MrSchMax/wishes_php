<?php

namespace app\models;

use app\Db;

abstract class AbstractModelMtoM extends AbstractModel
{
    use TraitModelWithOwner;

    protected const LINKED_TABLE = '';
    protected const LINKED_TABLE_KEY = '';

    public static function fromIds($ownerId, $linkedId) {
        return new (static::class)([
            static::OWNER_KEY => $ownerId,
            static::LINKED_TABLE_KEY=> $linkedId
        ]);
    }

    public static function insertObjects($ownerId, array $objects) {
        foreach ($objects as $obj) {
            $item = static::fromIds($ownerId, $obj->id);
            $item->insert();
        }
    }

    public static function findByValue($key, $value): array
    {
        $db=Db::instance();
        $sql=
            'SELECT * FROM ' . static::TABLE .
            ' INNER JOIN ' . static::LINKED_TABLE . ' ON ' . static::LINKED_TABLE_KEY . '=id' .
            ' WHERE ' . $key . '=:' .$key;
        return $db->query($sql, static::class, [$key => $value]);
    }

    public function remove(): void
    {
        $columns = implode(
            ' AND ',
            array_map(
                fn ($key) => $key . '=:' . $key,
                [static::LINKED_TABLE_KEY, static::OWNER_KEY]
            )
        );

        $sql =
            'DELETE FROM ' . static::TABLE .
            ' WHERE ' . $columns;

        $db = Db::instance();
        $db->execute($sql, $this->select(array_keys(static::VALIDATORS)));
    }
}
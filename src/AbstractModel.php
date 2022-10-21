<?php

namespace app;

use app\utils\Arrays;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

abstract class AbstractModel
{
    protected const TABLE = '';
    protected const VALIDATORS =[];
    protected const VALIDATION_MSGS = [];

    public function __construct(Array $properties=array()){
        foreach($properties as $key => $value){
            $this->{$key} = $value;
        }
    }

    public static function findAll(): array
    {
        $db=Db::instance();
        $sql= 'SELECT * FROM ' . static::TABLE;
        return $db->query($sql, static::class);
    }

    public static function getFirst($data): ?object
    {
        return empty($data) ? null : $data[0];
    }

    public static function findById($id): ?object
    {
        return self::findFirstByValue('id', $id);
    }

    public static function findByValue($key, $value): ?array
    {
        $db=Db::instance();
        $sql= 'SELECT * FROM ' . static::TABLE . ' WHERE ' . $key . '=:' .$key;
        return $db->query($sql, static::class, [$key => $value]);
    }

    public static function findFirstByValue($key, $value): ?object
    {
        return self::getFirst(self::findByValue($key, $value));
    }

    public function validation(?array $keys = null, bool $isInclude = true): Validation
    {

        $rules = $keys
            ? Arrays::select(static::VALIDATORS, $keys, $isInclude)
            : static::VALIDATORS
            ;

        $validator = new Validator(static::VALIDATION_MSGS);
        return $validator->validate(get_object_vars($this), $rules);
    }

    public function select(array $keys, bool $isInclude = true): array
    {
        return Arrays::select(get_object_vars($this), $keys, $isInclude);
    }

    public function insert(): void
    {
        $data = $this->select(array_keys(static::VALIDATORS));

        $columns = array_keys($data);
        $binds = array_map(fn ($key) => ':' . $key, array_keys($data));

        $sql = 'INSERT INTO ' . static::TABLE . '
        (' . implode(',', $columns) . ')
        VALUES (' . implode(',', $binds) . ' )';

        $db = Db::instance();
        $db->execute($sql, $data);
        $this->id = $db->lastId();
    }
}
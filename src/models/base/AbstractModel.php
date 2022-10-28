<?php

namespace app\models\base;

use app\Db;
use app\utils\Arrays;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

abstract class AbstractModel
{
    protected const TABLE = '';
    protected const MAIN_ID = 'id';
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
        return static::findFirstByValue(static::MAIN_ID, $id);
    }

    public static function findByValue($key, $value): array
    {
        $db=Db::instance();
        $sql= 'SELECT * FROM ' . static::TABLE . ' WHERE ' . $key . '=:' .$key;
        return $db->query($sql, static::class, [$key => $value]);
    }

    public static function findFirstByValue($key, $value): ?object
    {
        return static::getFirst(static::findByValue($key, $value));
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

    protected function prepareData(): array
    {
        $data = $this->select(array_keys(static::VALIDATORS));

        foreach ($data as $key => $value) {
            if (gettype($value) == 'boolean') {
                $data[$key] = $value ? 1 : 0;
            }
        }

        return $data;
    }

    public function insert(): void
    {
        $data = $this->prepareData();

        $columns = array_keys($data);
        $binds = array_map(fn ($key) => ':' . $key, array_keys($data));


        $sql = 'INSERT INTO ' . static::TABLE . '
        (' . implode(',', $columns) . ')
        VALUES (' . implode(',', $binds) . ' )';

        $db = Db::instance();
        $db->execute($sql, $data);
        $this->id = $db->lastId();
    }

    public function update(): ?object
    {
        $data = $this->prepareData();

        $columns = implode(
            ',',
            array_map(fn ($key) => $key . '=:' . $key, array_keys($data))
        );

        $sql = 'UPDATE ' . static::TABLE
            . ' SET ' . $columns
            . ' WHERE id=:id';

        $data[static::MAIN_ID] = $this->id;

        $db = Db::instance();
        $db->execute($sql, $data);

        return static::findById($this->id);
    }

    public static function delete($id): void
    {
        $sql = 'DELETE FROM ' . static::TABLE
            . ' WHERE '. static::MAIN_ID . '=:' . static::MAIN_ID;

        $db = Db::instance();
        $db->execute($sql, [static::MAIN_ID => $id]);
    }


    public static function validateId(array $data, string $field = 'id'): Validation
    {
        $validator = new Validator();
        return $validator->validate(
            $data,
            [$field => 'required|integer',]
        );
    }
}
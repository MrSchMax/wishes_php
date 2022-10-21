<?php

namespace app\models;

use app\AbstractModel;

class Category extends AbstractModel
{
    protected const TABLE = 'categories';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'userId' => 'required|integer',
    ];

    public static function findByUser(string $userId): array
    {
        return self::findByValue('userId', $userId);
    }
}
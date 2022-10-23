<?php

namespace app\models;

use app\models\base\AbstractModel;
use app\models\base\TraitModelWithOwner;

class Category extends AbstractModel
{
    use TraitModelWithOwner;

    protected const TABLE = 'categories';
    protected const OWNER_KEY = 'userId';
    protected const VALIDATORS = [
        'name' => 'required|min:2|max:100|regex:/^[-\p{L} _\d]+$/u',
        'userId' => 'required|integer',
    ];
}
<?php

namespace app\models;

use app\models\base\AbstractModel;
use app\models\base\TraitModelWithOwner;

class Gift  extends AbstractModel
{
    use TraitModelWithOwner;

    public int $id;

    protected const TABLE = 'gifts';
    protected const OWNER_KEY = 'userId';
    protected const VALIDATORS = [
        'name' => 'required|min:2|max:100|regex:/^[-\p{L} _\d]+$/u',
        'price' => 'required|regex:/^\d{1,10}(\.\d{1,2})?$/',
        'photo' => 'required|url',
        'link' => 'required|url',
        'userId' => 'required|integer',
    ];
}
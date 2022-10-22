<?php

namespace app\models;

use app\AbstractModelWithOwner;

class Category extends AbstractModelWithOwner
{
    protected const TABLE = 'categories';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'userId' => 'required|integer',
    ];
}
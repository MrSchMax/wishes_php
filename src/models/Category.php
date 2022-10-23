<?php

namespace app\models;

class Category extends AbstractModel
{
    use TraitModelWithOwner;

    protected const TABLE = 'categories';
    protected const OWNER_KEY = 'userId';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'userId' => 'required|integer',
    ];
}
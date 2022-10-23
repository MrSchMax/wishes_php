<?php

namespace app\models;

class Gift  extends AbstractModel
{
    use TraitModelWithOwner;

    protected const TABLE = 'gifts';
    protected const OWNER_KEY = 'userId';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'price' => 'required|regex:/^\d{1,10}(\.\d{1,2})?$/',
        'photo' => 'required|url',
        'link' => 'required|url',
        'userId' => 'required|integer',
    ];
}
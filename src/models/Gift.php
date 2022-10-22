<?php

namespace app\models;

use app\AbstractModelWithOwner;

class Gift  extends AbstractModelWithOwner
{
    protected const TABLE = 'gifts';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'product_price' => 'required|regex:/^\d{1,10}(\.\d{1,2})?$/',
        'photo' => 'required|url',
        'link' => 'required|url',
        'userId' => 'required|integer',
    ];
}
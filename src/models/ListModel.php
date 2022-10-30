<?php

namespace app\models;

use app\models\base\AbstractModel;
use app\models\base\TraitModelWithArrays;
use app\models\base\TraitModelWithOwner;

class ListModel extends AbstractModel
{
    use TraitModelWithArrays;
    use TraitModelWithOwner;
    public bool $public;
    public int $id;

    protected const TABLE = 'lists';
    protected const OWNER_KEY = 'userId';
    protected const VALIDATORS = [
        'name' => 'required|min:2|max:100|regex:/^[-\p{L} _\d]+$/u',
        'public' => 'default:false|boolean',
        'userId' => 'required|integer',
    ];

    protected const ARRAYS = [
        [
            'baseModel' => Category::class,
            'mtomModel' => ListCategories::class,
            'fieldName' => 'categories'
        ],
        [
            'baseModel' => Gift::class,
            'mtomModel' =>  ListGifts::class,
            'fieldName' => 'gifts'
        ]
    ];

    public function hasAccess($userId): bool
    {
        return $this->public || $this->userId ==  $userId;
    }


}
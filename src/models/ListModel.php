<?php

namespace app\models;

class ListModel extends AbstractModelWithOwner
{
    use TraitModelWithArrays;
    public bool $public;

    protected const TABLE = 'lists';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
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
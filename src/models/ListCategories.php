<?php

namespace app\models;

use app\models\base\AbstractModelMtoM;

class ListCategories extends AbstractModelMtoM
{
    protected const TABLE = 'list_categories';
    protected const LINKED_TABLE = 'categories';
    protected const LINKED_TABLE_KEY = 'categoryId';
    protected const OWNER_KEY = 'listId';
    protected const MAIN_ID = 'listId';

    protected const VALIDATORS = [
        'listId' => 'required|integer',
        'categoryId' => 'required|integer',
    ];
}
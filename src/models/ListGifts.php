<?php

namespace app\models;

use app\models\base\AbstractModelMtoM;

class ListGifts extends AbstractModelMtoM
{
    protected const TABLE = 'list_gifts';
    protected const LINKED_TABLE = 'gifts';
    protected const LINKED_TABLE_KEY = 'giftId';
    protected const OWNER_KEY = 'listId';
    protected const MAIN_ID = 'listId';

    protected const VALIDATORS = [
        'listId' => 'required|integer',
        'giftId' => 'required|integer',
    ];
}
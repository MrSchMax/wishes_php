<?php

namespace app\models;

use app\AbstractModelWithOwner;

class ListModel extends AbstractModelWithOwner
{
    public bool $public;

    protected const TABLE = 'lists';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'public' => 'default:false|boolean',
        'userId' => 'required|integer',
    ];

    public function hasAccess($userId): bool
    {
        return $this->public || $this->userId ==  $userId;
    }
}
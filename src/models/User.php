<?php

namespace app\models;

use app\models\base\AbstractModel;

class User extends AbstractModel
{
    protected const TABLE = 'users';
    protected const VALIDATORS = [
        'name' => 'required|alpha_spaces|min:2',
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    public function prepare() {
        if ($this->password) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }

    public static function findByEmail(string $email): ?object
    {
        return static::findFirstByValue('email', $email);
    }

    public function getVerified(): ?object
    {
        $user = static::findByEmail($this->email);
        return $user && password_verify($this->password, $user->password)
            ? $user
            : null
            ;
    }
}
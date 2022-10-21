<?php

namespace app\models;

use app\AbstractModel;

class User extends AbstractModel
{
    protected const TABLE = 'users';
    protected const VALIDATORS = [
        'name' => 'required|alpha|min:2',
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
        return self::findFirstByValue('email', $email);
    }

    public function getVerified(): ?object
    {
        $user = self::findByEmail($this->email);
        return $user && password_verify($this->password, $user->password)
            ? $user
            : null
            ;
    }
}
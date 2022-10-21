<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use app\models\User;
use app\utils\Auth;
use app\utils\Response;

$userId = Auth::getUserId();

try {
    Response::sendOk([
        'message' => 'Успешно',
        'data' => User::findById($userId)->select(['password'], false)
    ]);
} catch (Exception $e) {
    Response::sendServerError();
}
<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use app\models\User;
use app\utils\Auth;
use app\utils\Response;
use app\utils\Cors;
Cors::cors();

$userId = Auth::getUserIdOrStop();

try {
    Response::sendOk(User::findById($userId)->select(['password'], false));
} catch (Exception $e) {
    Response::sendServerError();
}
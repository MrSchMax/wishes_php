<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\models\Category;
use app\utils\Auth;
use app\utils\Response;

$userId = Auth::getUserId();

try {
    Response::sendOk(Category::findByOwner($userId));
} catch (Exception $e) {
    Response::sendServerError();
}
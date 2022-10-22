<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\utils\Auth;
use app\utils\Response;

if (isset($class)) {
    $userId = Auth::getUserIdOrStop();
    try {
        Response::sendOk($class::findByOwner($userId));
    } catch (Exception $e) {
        Response::sendServerError();
    }
} else {
    Response::sendNotFound();
}
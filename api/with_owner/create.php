<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\utils\Auth;
use app\utils\Response;
use app\utils\Cors;
Cors::cors();

$userId = Auth::getUserIdOrStop();

$data = json_decode(file_get_contents("php://input"), true);
$data['userId'] = $userId;

$item = new $class($data);
$validation = $item->validation();

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
}
else {
    try {
        $item->insert();

        Response::sendOk($item);

    } catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError();
        }
        else {
//            var_dump($e);
            Response::sendServerError();
        }
    }
}
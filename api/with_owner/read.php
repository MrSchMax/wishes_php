<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


use app\utils\Auth;
use app\utils\Response;
if (isset($class)) {

    $userId = Auth::getUserIdOrStop();

    $data = json_decode(file_get_contents("php://input"), true);

    $validation = $class::validateId($data);

    if ($validation->fails()) {
        Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
    } else {
        try {
            $item = $class::findById($data['id']);

            if ($item && $item->userId == $userId) {
                Response::sendOk($item);
            } else {
                Response::sendNotFound();
            }
        } catch (Exception $e) {
            Response::sendServerError();
        }
    }
} else {
    Response::sendNotFound();
}
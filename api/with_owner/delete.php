<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\utils\Auth;
use app\utils\Response;

$userId = Auth::getUserIdOrStop();

$data = json_decode(file_get_contents("php://input"), true);

$validation = $class::validateId($data);

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
} else {
    try {
        $id = $data['id'];
        $item = $class::getCurrentForOwner($id, $userId);
        if ($item) {
            $class::delete($id);
            Response::sendOk($item);
        } else {
            Response::sendNotFound();
        }
    } catch (Exception $e) {
        Response::sendServerError();
    }
}
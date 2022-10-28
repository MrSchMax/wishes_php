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

$validationId = $class::validateId($data);
$validationData = $item->validation();

if ($validationId->fails() || $validationData->fails()) {
    Response::sendValidationError(['validation' =>
        array_merge(
            $validationId->errors()->toArray(),
            $validationData->errors()->toArray()
        )
    ]);
} else {
    try {
        if ($class::getCurrentForOwner($item->id, $userId)) {
            $result = $item->update();
            Response::sendOk($result);
        } else {
            Response::sendNotFound();
        }
    } catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError();
        }
        else {
            Response::sendServerError();
        }
    }
}
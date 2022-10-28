<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


use app\models\ListModel;
use app\utils\Auth;
use app\utils\Response;
use app\utils\Cors;
Cors::cors();


$data = json_decode(file_get_contents("php://input"), true);

$validation = ListModel::validateId($data);

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
} else {
    try {
        $item = ListModel::findById($data['id']);

        if ($item && $item->hasAccess(Auth::getUserId())) {
            Response::sendOk($item);
        } else {
            Response::sendNotFound();
        }
    } catch (Exception $e) {
        Response::sendServerError();
    }
}
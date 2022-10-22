<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\models\Category;
use app\utils\Auth;
use app\utils\Response;

$userId = Auth::getUserId();

$data = json_decode(file_get_contents("php://input"), true);

$validation = Category::validateId($data);

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
} else {
    try {
        $category = Category::findById($data['id']);

        if ($category && $category->userId == $userId) {
            Response::sendOk($category);
        } else {
            Response::sendNotFound();
        }
    } catch (Exception $e) {
        Response::sendServerError();
    }
}
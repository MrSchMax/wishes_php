<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use app\models\Category;
use app\utils\Auth;
use app\utils\Response;

$userId = Auth::getUserId();

$data = json_decode(file_get_contents("php://input"), true);
$data['userId'] = $userId;

$category = new Category($data);
$validation = $category->validation();

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
}
else {
    try {
        $category->insert();

        Response::sendOk($category);

    } catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError([
                'message' => 'Недомустимое имя',
                'error' => ['db' => 'Категория с таким именем уже существует']
            ]);
        }
        else {
            Response::sendServerError();
        }
    }
}
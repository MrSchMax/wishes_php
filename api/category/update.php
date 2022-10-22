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

$validationId = Category::validateId($data);
$validationData = $category->validation();

if ($validationId->fails() || $validationData->fails()) {
    Response::sendValidationError(['validation' =>
        array_merge(
            $validationId->errors()->toArray(),
            $validationData->errors()->toArray()
        )
    ]);
} else {
    try {

        if (Category::getCurrentForOwner($category->id, $userId)) {
            $category->update();
            Response::sendOk($category);
        } else {
            Response::sendNotFound();
        }
    } catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError([
                'message' => 'Недомустимое имя',
                'error' => ['db' => 'Категория с таким именем уже сществует']
            ]);
        }
        else {
            Response::sendServerError();
        }
    }
}
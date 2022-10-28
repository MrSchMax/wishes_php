<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use app\models\User;
use app\utils\Response;
use app\utils\Cors;
Cors::cors();

$data = json_decode(file_get_contents("php://input"), true);

$user = new User($data);
$validation = $user->validation();

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
}
else {
    $user->prepare();
    try {
        $user->insert();

        Response::sendOk($user->select(['name', 'email', 'id']));
    }
    catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError([
                'message' => 'Пользователь с таким email уже сществует',
                'error' => ['db' => 'Невозможно создать пользователя']
            ]);
        }
        else {
            Response::sendServerError();
        }
    }
}

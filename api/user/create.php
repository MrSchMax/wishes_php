<?php
header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use app\models\User;
use app\utils\Response;

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

        Response::sendOk([
            'message' => 'Пользователь успешно создан',
            'data' => $user->select(['name', 'email', 'id'])
        ]);
    }
    catch (Exception $e) {
        if (($e instanceof PDOException) && $e->getCode() == 23000) {
            Response::sendConflictError([
                'message' => 'Невозможно создать пользователя.',
                'error' => ['db' => 'Пользователь с таким email уже сществует']
            ]);
        }
        else {
            Response::sendServerError();
        }
    }
}

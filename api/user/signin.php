<?php

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use app\models\User;
use app\utils\Auth;
use app\utils\Response;

$data = json_decode(file_get_contents("php://input"), true);

$user = new User($data);
$validation = $user->validation(['email', 'password']);

if ($validation->fails()) {
    Response::sendValidationError(['validation' => $validation->errors()->toArray()]);
} else {
    try {
        $user = $user->getVerified();
        if ($user) {
            $user->jwt = Auth::generateJwt($user->id);

            Response::sendOk([
                'message' => 'Успешная авторизация',
                'data' => $user->select(['password'], false)
            ]);
        }
        else {
            Response::sendBadRequestError([
                'message' => 'Невозможно выполнить авторизацию',
                'error' => ['auth' => 'Неверная комбинания логина и пароля']
            ]);
        }
    } catch (Exception $e) {
        Response::sendServerError();
    }
}
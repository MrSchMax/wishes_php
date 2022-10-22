<?php

namespace app\utils;

class Response
{
    const OK_CODE = 200;
    const BAD_REQ_ERR_CODE = 400;
    const UNAUTH_ERR_CODE = 401;
    const FORBIDDEN_ERR_CODE = 403;
    const NOT_FOUND_ERR_CODE = 404;
    const CONFLICT_ERR_CODE = 409;
    const SERVER_ERR_CODE = 500;

    public static function send($code, $data): void
    {
        http_response_code($code);
        echo json_encode([$data]);
    }

    public static function sendNotFound(): void
    {
        self::send(
            self::NOT_FOUND_ERR_CODE,
            [
                'message' => 'Невозможно выполнить запрос',
                'error' => ["server" => "Запрашиваемый ресурс не найден"]
            ]
        );
    }

    public static function sendServerError(): void
    {
        self::send(
            self::SERVER_ERR_CODE,
            [
                'message' => 'Невозможно выполнить запрос',
                'error' => ["server" => "Внуренняя ошибка сервера"]
            ]
        );
    }

    public static function sendAuthError(): void
    {
        self::send(
            self::UNAUTH_ERR_CODE,
            [
                'message' => 'Требуется авторизация',
                'error' => ['auth' => 'Требуется авторизация']
            ]
        );
    }

    public static function sendOk(object|array $data): void
    {
        self::send(
            self::OK_CODE,
            [
                'message' => 'Успешно',
                'data' => $data
            ]
        );
    }

    public static function sendBadRequestError(array $data): void
    {
        self::send(
            self::BAD_REQ_ERR_CODE,
            $data
        );
    }

    public static function sendValidationError(array $errors): void
    {
        self::send(
            self::BAD_REQ_ERR_CODE,
            [
                'message' => 'Невозможно выполнить запрос. Данные не корректны',
                'error' => $errors
            ]
        );
    }

    public const CONFLICT_ERR_MSG = [
        'message' => 'Операция отклонена',
        'error' => ['db' => 'Данные с аналогичными параметрами уже существуют']
    ];

    public static function sendConflictError(array $data = self::CONFLICT_ERR_MSG): void
    {
        self::send(
            self::CONFLICT_ERR_CODE,
            $data
        );
    }
}
<?php
namespace app\utils;

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{

    public static function generateJwt($id): string
    {
        $now = strtotime("now");
        return JWT::encode([
            "iat" => $now,
            "nbf" => $now,
            "exp" => $now + 3600,
            "jti" => base64_encode(random_bytes(16)),
            "iss" => JWT_ISSUER,
            "aud" => JWT_AUD,
            "data" => [
                "id"    => $id,
            ]
        ], JWT_SECRET, JWT_ALGO);
    }

    public static function getUserId() {
        $id = null;
        try {
            $authStr = getallheaders()["Authorization"] ?? '';
            if ($authStr && str_starts_with($authStr, 'Bearer ')) {
                $jwt = substr(getallheaders()["Authorization"], 7);
                $res = JWT::decode($jwt, new Key(JWT_SECRET, JWT_ALGO));
                $id = $res->data->id ?? null;
            }
        }
        catch (\Exception $e) {
        }

        return $id;
    }

    public static function getUserIdOrStop()
    {
        $id = static::getUserId();
        if (!$id) {
            Response::sendAuthError();
            exit();
        }
        return $id;
    }
}
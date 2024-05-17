<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new UserModel();

        $login = $this->request->getVar('login');
        $password = $this->request->getVar('password');

        $user = $userModel->where('login', $login)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid email or password.'], 404);
        }

        if ($user['status'] == 'closed') {
            return $this->respond(['error' => 'Your account is not open'], 403);
        }

        $pwd_verify = password_verify($password, $user['password']);

        if (!$pwd_verify) {
            return $this->respond(['error' => 'Invalid email or password.'], 404);
        }

        $key = getenv('TOKEN_JWT_KEY');
        $iat = time();
        $exp = $iat + 3600;

        $payload = array(
            "iat" => $iat,
            "exp" => $exp,
            "uid" => $user['id'],
            "login" => $user['login'],
            "roles" => $user['roles'],
            "status" => $user['status'],
            "created_at" => $user['created_at']
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $payload['exp'] = $iat + 7200;

        $refreshToken = JWT::encode($payload, $key, 'HS256');

        $response = [
            'accessToken' => $token,
            'accessTokenExpiresAt' => $exp,
            'refreshToken' => $refreshToken,
            'refreshTokenExpiresAt' => $iat + 7200
        ];

        return $this->respond($response, 201);
    }

    public function verifyToken($token) {
        if (!$token) {
            return $this->respond(['message' => 'Token not found'], 404);
        }

        $key = getenv('TOKEN_JWT_KEY');

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return $this->respond(['message' => 'Invalid token'], 404);
        }

        $response = [
            'accessToken' => $token,
            'accessTokenExpiresAt' => $decoded->exp,
        ];

        return $this->respond($response, 200);
    }

    public function refresh($refreshToken)
    {
        if (!$refreshToken) {
            return $this->respond(['message' => 'Token not found'], 404);
        }

        $key = getenv('TOKEN_JWT_KEY');

        try {
            $decoded = JWT::decode($refreshToken, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return $this->respond(['message' => 'Invalid token'], 404);
        }

        $iat = time();
        $exp = $iat + 3600;

        $payload = array(
            "iat" => $iat,
            "exp" => $exp,
            "login" => $decoded->login,
            "roles" => $decoded->roles,
            "status" => $decoded->status
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $payload['exp'] = $iat + 7200;

        $refreshToken = JWT::encode($payload, $key, 'HS256');

        $response = [
            'accessToken' => $token,
            'accessTokenExpiresAt' => $exp,
            'refreshToken' => $refreshToken,
            'refreshTokenExpiresAt' => $iat + 7200
        ];

        return $this->respond($response, 201);
    }
}
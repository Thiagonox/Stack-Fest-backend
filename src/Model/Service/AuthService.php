<?php

namespace Stack\Fest\Model\Service;

use Exception;
use Firebase\JWT\JWT;
use Stack\Fest\Model\DAO\UserDAO;

class AuthService
{
    private UserDAO $userDAO;
    private string $key = 'secret';

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }

    public function loginUser($username, $password)
    {
        $user = $this->userDAO->getUserByNameWhereDeletedFalse($username);

        if ($user && password_verify($password, $user['password'])) {
            return $this->generateToken($user['id']);
        }

        return false;
    }

    private function generateToken($id): string
    {
        $tokenData = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7),
            'id' => $id,
        ];

        return JWT::encode($tokenData, $this->key);
    }

    /**
     * @throws Exception
     */
    public function isUserAuthenticated()
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $tokenParts = explode(" ", $authorizationHeader);
            if (count($tokenParts) == 2 && strtoupper($tokenParts[0]) === 'BEARER') {
                $token = $tokenParts[1];
                $details = JWT::decode($token, $this->key, array('HS256'));
                return $details->id;
            } else {
                throw new Exception('Invalid authorization header');
            }
        } else {
            throw new Exception('No token provided');
        }
    }
}
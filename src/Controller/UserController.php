<?php

namespace Stack\Fest\Controller;

use Exception;
use Stack\Fest\Config\Request;
use Stack\Fest\Config\Response;
use Stack\Fest\Config\Route;
use Stack\Fest\Model\DAO\UserDAO;
use Stack\Fest\Model\Entity\User;
use Stack\Fest\Model\Service\AuthService;

class UserController
{
    public function __construct(
        protected AuthService $authService,
        protected UserDAO     $userDAO,
        protected Request    $request,
        protected Response   $response
    )
    {
    }

    #[Route("/api/v1/users/register", "POST")]
    public function registerUser()
    {
        try {
            $body = $this->request->getBody();

            $user = new User(
                $body['name'],
                $body['email'],
                $body['password'],
                $body['phone']
            );

            $userExits = $this->userDAO->getUserByName($user->getName());
            if ($userExits) {
                throw new Exception('User already exists');
            }

            $this->response->json(201, ['user' => $this->userDAO->registerUser($user)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/users/login", "POST")]
    public function loginUser()
    {
        try {
            $body = $this->request->getBody();

            $username = $body['name'];
            $password = $body['password'];

            $token = $this->authService->loginUser($username, $password);

            if ($token) {
                $this->response->json(200, ['token' => $token]);
            } else {
                throw new Exception('Invalid username or password');
            }
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/users", "GET")]
    public function getAllUser()
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['users' => $this->userDAO->getAllUsers()]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/users/{id}", "GET")]
    public function getUserById($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['user' => $this->userDAO->getUserById($parameters->id)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/users/{id}", "PUT")]
    public function updateUser($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $body = $this->request->getBody();

            $userExists = $this->userDAO->getUserByName($body['name']);
            if ($userExists) {
                throw new Exception('User already exists');
            }

            $user = new User(
                $body['name'],
                $body['email'],
                $body['password'],
                $body['phone']
            );

            $this->response->json(200, ['user' => $this->userDAO->updateUser($parameters->id, $user)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/users/{id}", "DELETE")]
    public function deleteUser($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['user' => $this->userDAO->deleteUser($parameters->id)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Managers\AuthenticationManager;
use App\Managers\ClientManager;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticationController extends AbstractController
{

    const LOGIN_SUCCESS = 'Login successful';
    const REGISTER_SUCCESS = 'Registration successful';
    const REGISTER_FAILED = 'Registration failed';
    const LOGOUT_SUCCESS = 'Logout successful';
    const PASSWORD_RESET = 'Password reset link was sent';

    /**
     * @var AuthenticationManager
     */
    private $authenticationManager;

    /**
     * @var ClientManager
     */
    private $clientManager;

    public function __construct(AuthenticationManager $authenticationManager, ClientManager $clientManager)
    {
        $this->authenticationManager = $authenticationManager;
        $this->clientManager = $clientManager;
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|max:255',
                'password' => 'required|max:255',
            ]
        );
        $token = $this->authenticationManager->login($request->request->all());

        return $this->createResponse(['message' => self::LOGIN_SUCCESS, 'token' => $token], Response::HTTP_CREATED);
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $this->authenticationManager->register($request->request->all());
        } catch (QueryException $exception) {
            return $this->createResponse(['message' => self::REGISTER_FAILED], Response::HTTP_FORBIDDEN);
        }

        return $this->createResponse(['message' => self::REGISTER_SUCCESS], Response::HTTP_CREATED);
    }

    public function registerClient(Request $request): JsonResponse
    {
        $request = $request->request->all();
        try {
            $clientId = $this->clientManager->createClient([Client::NAME => $request['client_name']);
            unset($request['client_name']);
            $userId = $this->authenticationManager->register($request);
        } catch (\Exception $exception) {
            return $this->createResponse(['message' => self::REGISTER_FAILED], Response::HTTP_FORBIDDEN);
        }

        $this->clientManager->addUser($clientId, $userId);

        return $this->createResponse(['message' => self::REGISTER_SUCCESS], Response::HTTP_CREATED);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authenticationManager->logout($request->header('token', ''));
        } catch (ModelNotFoundException $exception) {
            // TODO : log that logout failed
        }

        return $this->createResponse(['message' => self::LOGOUT_SUCCESS], Response::HTTP_OK);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $this->authenticationManager->resetPassword($request->request->all());
        } catch (ModelNotFoundException $exception) {
            // TODO : log that trying to reset a password for non existent user
        }

        return $this->createResponse(['message' => self::PASSWORD_RESET], 201);
    }
}

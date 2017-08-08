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

    const LOGIN_RULES = [
        'email' => 'required|email|max:255',
        'password' => 'required|max:255',
    ];

    const REGISTER_RULES = [
        'email' => 'required|email|max:255',
        'password' => 'required|max:255',
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
    ];

    const REGISTER_CLIENT_RULES = [
        'company' => 'required|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|max:255',
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
    ];
    const RESET_PASSWORD_RULES = [
        'email' => 'required|email|max:255',
    ];

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
        $this->validate($request, self::LOGIN_RULES);
        $token = $this->authenticationManager->login($request->only(array_keys(self::LOGIN_RULES)));

        return $this->createResponse(['message' => self::LOGIN_SUCCESS, 'token' => $token], Response::HTTP_CREATED);
    }

    public function register(Request $request): JsonResponse
    {
        $this->validate($request, self::REGISTER_RULES);
        try {
            $this->authenticationManager->register($request->only(array_keys(self::REGISTER_RULES)));
        } catch (QueryException $exception) {
            return $this->createResponse(['message' => self::REGISTER_FAILED], Response::HTTP_FORBIDDEN);
        }

        return $this->createResponse(['message' => self::REGISTER_SUCCESS], Response::HTTP_CREATED);
    }

    public function registerClient(Request $request): JsonResponse
    {
        $this->validate($request, self::REGISTER_CLIENT_RULES);
        try {
            $clientId = $this->clientManager->createClient([Client::NAME => $request['client_name']]);
            $userId = $this->authenticationManager->register($request->only(self::REGISTER_RULES));
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
        $this->validate($request, self::RESET_PASSWORD_RULES);
        try {
            $this->authenticationManager->resetPassword($request->get('email'));
        } catch (ModelNotFoundException $exception) {
            // TODO : log that trying to reset a password for non existent user
        }

        return $this->createResponse(['message' => self::PASSWORD_RESET], 201);
    }
}

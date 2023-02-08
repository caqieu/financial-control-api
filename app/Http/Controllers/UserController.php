<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;

class UserController
{
    protected UserService $serviceInstance;

    public function __construct(UserService $userService)
    {
        $this->serviceInstance = $userService;
    }

    public function create(CreateUserRequest $request)
    {
        $params = $request->validated();

        $user = $this->serviceInstance->create($params);

        return response()->json([
            'error' => false,
            'message' => 'Usuário criado com sucesso.',
            'data' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $token = $this->serviceInstance
            ->login(
                $request->input('email'),
                $request->input('password')
            );

        return response()->json([
            'error' => false,
            'data' => $token
        ]);
    }
}

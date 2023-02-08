<?php

namespace App\Services;

use App\Exceptions\UnauthenticatedHttpException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected User $modelInstance;

    public function __construct(User $user)
    {
        $this->modelInstance = $user;
    }

    public function create(array $params): User
    {
        return $this->modelInstance->create($params);
    }

    public function login(string $email, string $password): string
    {
        $user = $this->modelInstance->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new UnauthenticatedHttpException();
        }

        return $user->createToken("")->plainTextToken;
    }

}

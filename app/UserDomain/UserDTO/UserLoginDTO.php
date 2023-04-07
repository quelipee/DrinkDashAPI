<?php

namespace App\UserDomain\UserDTO;

use App\UserDomain\Requests\UserLoginRequest;

class UserLoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ){}

    public static function fromLoginRequestValidated(UserLoginRequest $loginRequest): UserLoginDTO
    {
        return new self(
            email: $loginRequest->validated('email'),
            password: $loginRequest->validated('password'),
        );
    }
}

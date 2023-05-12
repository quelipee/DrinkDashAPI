<?php

namespace App\UserAdmDomain\AdmDTO;

use App\UserAdmDomain\Requests\admRequest;

class AdmDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    )
    {}

    public static function fromRequestValidated(admRequest $request): AdmDTO
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );
    }
}

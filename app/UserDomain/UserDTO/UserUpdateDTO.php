<?php

namespace App\UserDomain\UserDTO;

use App\UserDomain\Requests\UserRequest;
use App\UserDomain\Requests\UserUpdateRequest;

class UserUpdateDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?string $address_delivery,
        public readonly ?string $address_billing,
        public readonly ?string $phone_number
    ){}

    public static function fromRequestValidated(UserUpdateRequest $userRequest): UserUpdateDTO
    {
        return new self(
            name: $userRequest->validated('name'),
            email: $userRequest->validated('email'),
            password: $userRequest->validated('password'),
            address_delivery: $userRequest->validated('address_delivery'),
            address_billing: $userRequest->validated('address_billing'),
            phone_number: $userRequest->validated('phone_number'),
        );
    }
}

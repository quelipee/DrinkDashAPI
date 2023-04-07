<?php

namespace App\UserDomain\UserService;

use App\Models\Client;
use App\Models\User;
use App\UserDomain\Repository\UserRepository;
use App\UserDomain\UserDTO\UserDTO;
use App\UserDomain\UserDTO\UserLoginDTO;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserService
{

    public function __construct(
        public UserRepository $repository
    ){}

    /**
     * @throws Exception
     */
    public function store(UserDTO $userDTO)
    {
        $all_users = $this->repository->all_users();

        foreach ($all_users as $all)
        {
            if ($all->email == $userDTO->email)
            {
                throw new Exception('Email ja cadastrado!!');
            }
        }

        $user = new User([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => $userDTO->password
        ]);
        $user->save();

         $client = new Client([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'address_delivery' => $userDTO->address_delivery,
            'address_billing' => $userDTO->address_billing,
            'phone_number' => $userDTO->phone_number,
        ]);
         $client->user()->associate($user);
         $client->save();

         return $client;
    }

    /**
     * authenticate vai verificar se o usuario consta no banco de dados
     *
     * @throws Exception
     */
    public function authenticate(UserLoginDTO $loginDTO)
    {
        $credentials = [
            'email' => $loginDTO->email,
            'password' => $loginDTO->password
        ];

        if (!Auth::attempt($credentials))
        {
            throw new Exception('Usuario não encontrado, email ou senha invalidas!!');
        }

        session()->start();
        $user = User::find(Auth::id());
        return $user->load('client');
    }

    /**
     * @throws Exception
     */
    public function destroySession(): bool
    {
        if (!Auth::check())
        {
            throw new Exception('Usuario não esta logado!!!');
        }

        Auth::logout();
        return true;
    }
}

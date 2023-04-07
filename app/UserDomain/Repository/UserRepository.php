<?php

namespace App\UserDomain\Repository;

use App\Models\Client;
use App\Models\User;

class UserRepository
{
    public function all_users()
    {
        return User::all();
    }

    public function find_user($id)
    {
        return User::find($id);
    }

    public function get_client($data): object
    {
        return Client::query()->where('user_id',$data->id)->first();
    }
}

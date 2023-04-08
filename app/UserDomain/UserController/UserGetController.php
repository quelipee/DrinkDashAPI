<?php

namespace App\UserDomain\UserController;

use App\Http\Controllers\Controller;
use App\ProductDomain\Resources\OrderResource;
use App\UserDomain\Repository\UserRepository;
use App\UserDomain\Resources\UserResouce;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserGetController extends Controller
{
    public function __construct(
        public UserRepository $userRepository,
    )
    {}

    public function get_user(): UserResouce
    {
        $user = $this->userRepository->get_client(Auth::user());
        return UserResouce::make($user);
    }

    public function get_list_order(): AnonymousResourceCollection
    {
        $user = $this->userRepository->get_client(Auth::user());
        return OrderResource::collection($user->orders);
    }
}

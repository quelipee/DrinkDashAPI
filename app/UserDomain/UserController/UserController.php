<?php

namespace App\UserDomain\UserController;

use App\Http\Controllers\Controller;
use App\UserDomain\Requests\UserLoginRequest;
use App\UserDomain\Requests\UserRequest;
use App\UserDomain\UserDTO\UserDTO;
use App\UserDomain\UserDTO\UserLoginDTO;
use App\UserDomain\UserService\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        public UserService $userService
    ) {}

    /**
     * @throws Exception
     */
    public function store(UserRequest $userRequest): JsonResponse
    {
        $created =  $this->userService->store(UserDTO::fromRequestValidated($userRequest));
        return response()->json($created, Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function login(UserLoginRequest $loginRequest): JsonResponse
    {
        $login = $this->userService->authenticate(UserLoginDTO::fromLoginRequestValidated($loginRequest));
        return response()->json($login, Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function logout(): JsonResponse
    {
        $this->userService->destroySession();
        if (!Auth::user())
        {
            return response()->json([],Response::HTTP_FOUND);
        }
        try {
            throw new Exception('Error');
        }catch (Exception $e)
        {
            return response()->json($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }
}

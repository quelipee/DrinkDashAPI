<?php

namespace App\UserDomain\UserController;

use App\Http\Controllers\Controller;
use App\ProductDomain\Resources\OrderResource;
use App\UserDomain\Requests\UserLoginRequest;
use App\UserDomain\Requests\UserRequest;
use App\UserDomain\Requests\UserUpdateRequest;
use App\UserDomain\UserDTO\UserDTO;
use App\UserDomain\UserDTO\UserLoginDTO;
use App\UserDomain\UserDTO\UserUpdateDTO;
use App\UserDomain\UserService\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $user =  $this->userService->store(UserDTO::fromRequestValidated($userRequest));
        return response()->json($user,Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function login(UserLoginRequest $loginRequest): JsonResponse
    {
        $token = $this->userService->authenticate(UserLoginDTO::fromLoginRequestValidated($loginRequest));
        return response()->json($token, Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function logout()
    {
        $logout =  $this->userService->destroySession();

        if(!$logout){
            throw new Exception("Error Processing Request", 1);
        }

        return $logout;

    }


    /**
     * @throws Exception
     */
    public function editProfile(UserUpdateRequest $request): JsonResponse
    {
        $edit_profile = $this->userService->edit_user(UserUpdateDTO::fromRequestValidated($request));

        return response()->json($edit_profile, Response::HTTP_CREATED);
    }


    /**
     * @throws Exception
     */
    public function deposit(Request $request): JsonResponse
    {
        $deposit = $this->userService->user_deposit($request);

        if (!$deposit)
        {
            return response()->json(['message' => 'Erro ao realizar o depósito.'],Response::HTTP_NOT_FOUND);
        }
        return response()->json($deposit,Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function order_product($id, Request $request) //pedir produto
    {
        /*
         * TODO CONFIGURAR A QUANTIDADE QUE RECEBE DO INPUT DO FRONT END, ONDE RECEBE AQUI NO (REQUEST)
         *
         * esse request esta voltando as informações do usuario e a quantidade do produto que vai solicitar
         *
         * */
        $order = $this->userService->order_product($id,$request);
        return OrderResource::make($order);
    }


    public function order_product_cancel($id){
        $cancel_order = $this->userService->cancelProduct($id);

        return response()->json($cancel_order,Response::HTTP_CREATED);
    }


    /**
     * @throws Exception
     */
    public function buy_product($id): JsonResponse
    {
        /*
         * a variavel $id esta mostrando qual pedido que o usuario vai pagar
         * */
        $pay = $this->userService->buy_product($id);
        return response()->json($pay,Response::HTTP_CREATED);
    }
}

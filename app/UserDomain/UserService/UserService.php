<?php

namespace App\UserDomain\UserService;

use App\Enums\OrderStatusEnum;
use App\Models\Balance;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\UserDomain\Repository\UserRepository;
use App\UserDomain\UserDTO\UserDTO;
use App\UserDomain\UserDTO\UserLoginDTO;
use App\UserDomain\UserDTO\UserUpdateDTO;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{

    public function __construct(
        public UserRepository $repository
    ){}

    /**
     * @throws Exception
     */
    public function store(UserDTO $userDTO): User
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

         $balance = new Balance([
             'balance' => 0
         ]);
         $balance->client()->associate($client);
         $balance->save();

         return $user;
    }

    /**
     * authenticate vai verificar se o usuario consta no banco de dados
     *
     * @throws Exception
     */
    public function authenticate(UserLoginDTO $loginDTO): array
    {
        $credentials = [
            'email' => $loginDTO->email,
            'password' => $loginDTO->password
        ];

        if (!Auth::attempt($credentials))
        {
            throw new Exception('Usuario não encontrado, email ou senha invalidas!!');
        }

        $user = Auth::user();

        if ($user->isAdmin != 0){
            throw new Exception('Usuario não encontrado, email ou senha invalidas!!');
        }

        $token = $user->createToken($loginDTO->email)->plainTextToken;

        return [
            'email' => $loginDTO->email,
            'password' => $loginDTO->password,
            'token' => $token
        ];
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

        $user = Auth::user();
        $user->tokens()->delete();
        // $user->currentAccessToken()->delete();

        return true;
    }

    /**
     * @throws Exception
     */
    public function edit_user(UserUpdateDTO $userDTO): object
    {
        $client = $this->repository->get_client(Auth::user());

        if(!$client){
            throw new Exception('Erro: Usuario não encontrado!!');
        }

        $client->fill(array_filter((array)$userDTO));
        $client->save();
        return $client;
    }

    /**
     * @throws Exception
     */
    public function user_deposit(Request $request)
    {
        if (!Auth::user())
        {
            throw new Exception('Usuario não está logado!!');
        }

        $client = $this->repository->get_client(Auth::user());
        $balance_deposit = $this->repository->get_balance_client($client);

        if (!$balance_deposit)
        {
            throw new Exception('Usuario não possui uma carteira!!!');
        }

        $balance_deposit->fill([
            'balance' => ($balance_deposit->balance + $request->deposit)
        ]);
        $balance_deposit->save();

        return $balance_deposit;
    }

    public function order_product($id, $request)
    {
        $product = Product::find($id);
        $stock_prod = $product->stock;
        $user = Auth::user();

        if ($stock_prod->available_quantity <= 0)
        {
            throw new Exception('Erro: Não tem produto no estoque!!!');
        }
        elseif($request->qtd > $stock_prod->available_quantity){
            throw new Exception('Erro: Não possui essa quantidade no estoque!!');
        }

        $client = $this->repository->get_client($user);

        if ($request->qtd == null || $request->qtd == 0)
        {
            throw new Exception('Erro: Usuario não informou a quantidade!!!');
        }

        $order = new Order([
            'date_order' => Carbon::now(),
            'status_order' => OrderStatusEnum::PENDING,
        ]);
        $order->client()->associate($client);
        $order->save();

        $order_item = new OrderItem([
            'amount' => $request->qtd, // TODO ESTA INFORMAÇÃO TEM QUE VIM DO INPUT DO FRONT PARA SABER A QUANTIDADE QUE O USUARIO QUER SOLICITAR PARA COMPRAR
            'unit_price' => $product->price,
        ]);
        $order_item->order()->associate($order);
        $order_item->product()->associate($product);
        $order_item->save();

        $stock = Stock::find($stock_prod->id);
        $stock->fill([
            'available_quantity' => ($stock_prod->available_quantity - $request->qtd)
        ]);
        $stock->save();

        /*
         *
         *
         * pegando o ultimo pedido que o cliente fez e retornando para o controller
         *
         *
         * */
        return $client->orders()->latest('date_order')->first();
    }

    /**
     * @throws Exception
     */
    public function buy_product($id):string
    {
        $order = Order::find($id);
        if (!$order)
        {
            throw new Exception('Erro pedido não encontrado!!!');
        }

        $client = $this->repository->get_client(Auth::user());
        $balance = $this->repository->get_balance_client($client);

        $order_pay = $order->orders_items;
        $value_product = ($order_pay[0]->amount * $order_pay[0]->unit_price);

        if ($balance->balance < $value_product)
        {
            throw new Exception('Não possui saldo suficiente!!');
        }

        if($order === 'Concluído'){
            throw new Exception('Esse produto ja foi comprado!!!');
        }

        if($client->id != $order->client_id){
            throw new Exception('Pedido não encontrado!!!');
        }

        $balance->fill([
            'balance' => $balance->balance - $value_product
        ]);
        $balance->save();

        $order->fill([
            'status_order' => OrderStatusEnum::COMPLETED
        ]);
        $order->save();

        return $order;
    }

    public function cancelProduct($id)
    {
        $client = $this->repository->get_client(Auth::user());
        $order = Order::find($id);

        if($order->client_id != $client->id){
            throw new Exception("Erro: Esse usuario nao possui este pedido!!!");
        }

        else if($order->status_order != 'Pendente'){
            throw new Exception('Erro: Não pode cancelar um pedido concluído!!!');
        }

        $ordered_item = $order->orders_items[0];
        $product = [
            'product_id' => $ordered_item->product_id,
            'amount' => $ordered_item->amount,
        ];

        $stock_item = Stock::find($product['product_id']);
        $stock_item->fill([
            'available_quantity' => $stock_item->available_quantity += $product['amount']
        ]);
        $stock_item->save();

        $cancel_order = $order->orders_items()->where('id',$ordered_item->id)->first();
        $cancel_order->delete();
        $order->delete();

        return $client->orders;

    }
}

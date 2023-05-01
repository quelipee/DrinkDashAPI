<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\UserDomain\Repository\UserRepository;
use Exception;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /*
     * usuario se cadastrando no sistema
     *
     * */

    public function test_user_register()
    {
        //preapre
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 123,
            'address_delivery' => 'vila jose sipos filho n°72', //entrega de entrega
            'address_billing' => 'vila jose sipos filho n°72',//endereço de cobrança
            'phone_number' => 15996925812
        ];
        //act
        $response = $this->post('api/register', $payload);
        //assert
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /*
     * usuario tentando ser autenticado e logar
     *
     * */
    public function test_user_can_login()
    {
        //preapre
        $user = User::first();
        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 123
        ];
        //act
        $response = $this->post('api/login', $payload);
        //assert
        $this->assertDatabaseHas('users',['email' => $payload['email']]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_logout()
    {
        //prepare
        $payload = User::first();
        //act
        $response = $this->actingAs($payload)->post('api/logout');
        //assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_insert_product()
    {
        //prepare

        //act
        $response = $this->get('api/insert');
        //assert
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_get_all_products()
    {
        //prepare

        //act
        $response = $this->get('api/get_all_products');
        //assert
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */
    public function test_user_can_order_product()
    {
        //prepare
        $user = User::first();
        // $user['qtd'] = random_int(1,5);
        $qtd = random_int(1,5);
        $product = Product::find(random_int(0,24));
        //act
        $response = $this->actingAs($user)->post('api/order_product/' . $product->id, ['qtd' => $qtd]);
        // $response = $this->actingAs($user)->post('api/order_product/' . $product->id, $user->toArray());
        //assert
        $response->assertStatus(Response::HTTP_OK);

    }

    public function test_user_can_deposit()
    {
        //preapre
        $user = User::latest()->first();
        $payload = [
            'deposit' => 10000
        ];
        //act
        $response = $this->actingAs($user)->post('api/deposit',$payload);
        //assert
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_can_pay_product()
    {
        //prepare
        $user = User::latest()->first();
        $repository = new UserRepository();
        $client = $repository->get_client($user);
        $order = $client->orders[1];
        //act
        $response = $this->actingAs($user)->post('api/buy_product/' . $order->id);
        //assert
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_get_user()
    {
        //prepare
        $user = User::first();
        //act
        $response = $this->actingAs($user)->get('api/get-user');
        //assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_list_order()
    {
        //prepare
        $user = User::first();
        //act
        $response = $this->actingAs($user)->get('api/get-list-order');
        //assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_view_product()
    {
        //prepare
        $product = Product::first();
        //act
        $response = $this->get('api/product/' . $product->id);
        //assert
        $response->assertStatus(Response::HTTP_OK);
    }
}

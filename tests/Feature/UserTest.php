<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /*
     * usuario se cadastrando no sistema
     *
     *
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
        $payload = [
            'name' => 'felipe',
            'email' => 'fe@gmail.com',
            'password' => 123
        ];
        //act
        $response = $this->post('api/login', $payload);
        //assert
        $this->assertDatabaseHas('users',['name' => $payload['name']]);
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
    public function test_user_can_buy_product()
    {
        //prepare
        $user = User::first();
        $user['qtd'] = random_int(1,5);
        $product = Product::find(random_int(0,24));
        //act
        $response = $this->actingAs($user)->post('api/buy_product/' . $product->id, $user->toArray());
        //assert
        $response->assertStatus(Response::HTTP_OK);

    }
}
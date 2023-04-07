<?php

namespace App\ProductDomain\ProductService;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\UserDomain\Repository\UserRepository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function __construct(
        public Client $client,
        public UserRepository $repository
    ){}

    public function get_products_in_api()
    {
        return json_decode($this->client->get('https://api.punkapi.com/v2/beers?_limit=5')->getBody());
    }

    /**
     * @throws Exception
     */
    public function insert(): bool
    {
        $products = $this->get_products_in_api();

        foreach ($products as $product)
        {
            if (Product::where('name', $product->name)->exists()) {
                throw new Exception('Error: Produto já cadastrado!!!');
            }
            /*
             * firstorCreate ele verifica se ja existe um item com o mesmo nome, caso nao exista
             *  ele cria um item, se nao ele nao executa
             *
             * */

            $new_product = new Product([
                    'name' => $product->name,
                    'description' => $product->description,
                    'img_product' => $product->image_url,
                    'price' => random_int(5,99),
                    'category' => 'cerveja'
            ]);
            $new_product->save();

            $stock = new Stock([
                'available_quantity' => random_int(0,10),
            ]);
            $stock->product()->associate($new_product);
            $stock->save();
        }

        return true;
    }

    public function buy_product($id, $user): object
    {
        $product = Product::find($id);
        $client = $this->repository->get_client($user);
        $order = new Order([
            'date_order' => Carbon::now(),
            'status_order' => 'Comprado',
        ]);
        $order->client()->associate($client);
        $order->save();

        $order_item = new OrderItem([
            'amount' => $user->qtd, // TODO ESTA INFORMAÇÃO TEM QUE VIM DO INPUT DO FRONT PARA SABER A QUANTIDADE QUE O USUARIO QUER COMPRAR
            'unit_price' => $product->price,
        ]);
        $order_item->order()->associate($order);
        $order_item->product()->associate($product);
        $order_item->save();

        /*
         *
         *
         * pegando o ultimo pedido que o cliente fez e retornando para o controller
         *
         *
         * */
        return $client->orders()->latest('date_order')->first();
    }
}

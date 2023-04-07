<?php

namespace App\ProductDomain\ProductService;

use App\Enums\OrderStatusEnum;
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

    /**
     * @throws Exception
     */
    public function buy_product($id, $user): object
    {
        $product = Product::find($id);
        $stock_prod = $product->stock;

        if ($stock_prod->available_quantity <= 0)
        {
            throw new Exception('Error: Não tem produto no estoque!!!');
        }

        $client = $this->repository->get_client($user);
        $order = new Order([
            'date_order' => Carbon::now(),
            'status_order' => OrderStatusEnum::PENDING,
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

        $stock = Stock::find($stock_prod->id);
        $stock->fill([
            'available_quantity' => ($stock_prod->available_quantity - 1)
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
}

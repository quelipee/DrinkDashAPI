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

}

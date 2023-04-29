<?php

namespace App\UserDomain\UserController;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\ProductDomain\Resources\OrderResource;
use App\UserDomain\Repository\UserRepository;
use App\UserDomain\Resources\UserResouce;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserGetController extends Controller
{
    public function __construct(
        public UserRepository $userRepository,
    )
    {}

    public function get_user(): UserResouce
    {
        $user = $this->userRepository->get_client(Auth::user());
        $user['balance'] = $user->balance;
        return UserResouce::make($user);
    }

    public function get_list_order(): Collection
    {
        $user = $this->userRepository->get_client(Auth::user());
        $orders = Order::with('orders_items.product')->where('client_id', $user->id)->get();

        $products = new Collection();
        foreach ($orders as $order) {
            foreach ($order->orders_items as $item) {
                $product = [
                    'date_order' => $order->date_order,
                    'status_order' => $order->status_order,
                    'products' => [
                        'id_product' => $item->product->id,
                        'name' => $item->product->name,
                        'description' => $item->product->description,
                        'price' => $item->product->price,
                        'url_img' => $item->product->img_product,
                        'amount' => $item->amount,
                        'unit_price' => $item->unit_price,
                        'order_id' => $item->order_id,
                    ]
                ];
                $products->push($product);
            }
        }
        return $products;
    }

}

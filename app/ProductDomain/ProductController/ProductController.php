<?php

namespace App\ProductDomain\ProductController;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\ProductDomain\ProductService\ProductService;
use App\ProductDomain\Repository\ProductRepository;
use App\ProductDomain\Resources\OrderResource;
use App\ProductDomain\Resources\ProductResource;
use App\UserDomain\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService,
        public ProductRepository $repository,
        public UserRepository $userRepository,
    ){}

    /**
     * @throws Exception
     */
    public function insert_bd_products(): JsonResponse
    {
        $this->productService->insert();
        return response()->json([],Response::HTTP_CREATED);
    }

    public function get_all_products(): AnonymousResourceCollection
    {
        $products = $this->repository->get_all();
        $collection = new Collection($products);
        return ProductResource::collection($collection);
    }

    public function buy_product($id, Request $request)
    {
        //TODO CONFIGURAR A QUANTIDADE QUE RECEBE DO INPUT DO FRONT END, ONDE RECEBE AQUI NO (REQUEST)
        $order = $this->productService->buy_product($id,$request);
        return OrderResource::make($order);
    }
}

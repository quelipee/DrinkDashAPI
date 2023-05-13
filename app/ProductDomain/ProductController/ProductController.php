<?php

namespace App\ProductDomain\ProductController;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\ProductDomain\ProductService\ProductService;
use App\ProductDomain\Repository\ProductRepository;
use App\ProductDomain\Requests\ProductRequest;
use App\ProductDomain\Requests\ProductUpdateRequest;
use App\ProductDomain\Resources\OrderResource;
use App\ProductDomain\Resources\ProductResource;
use App\UserDomain\Repository\UserRepository;
use Illuminate\Http\RedirectResponse;
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

    public function insert_products(ProductRequest $request): RedirectResponse
    {
        $path = $request->file('img_product')->store('images','public');
        $this->productService->store($request->validated(),$path);
        return response()->redirectToRoute('index');
    }

    public function get_all_products(): AnonymousResourceCollection
    {
        $products = $this->repository->get_all();
        $collection = new Collection($products);
        return ProductResource::collection($collection);
    }

    public function getProduct($id)
    {
        $product = $this->repository->get_id_product($id);
        $product['stock'] = $product->stock;
        return $product;
    }

    /**
     * @throws Exception
     */

    public function edit_product_bd(ProductUpdateRequest $request, $id) : RedirectResponse
    {
        $this->productService->edit($request->validated(),$id);
        return response()->redirectToRoute('index')->setStatusCode(Response::HTTP_CREATED);
    }
}

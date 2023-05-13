<?php

namespace App\UserAdmDomain\AdmController;

use App\Http\Controllers\Controller;
use App\Models\Adm;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\UserAdmDomain\AdmDTO\AdmDTO;
use App\UserAdmDomain\AdmService\AdmService;
use App\UserAdmDomain\Requests\admRequest;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdmController extends Controller
{
    public function __construct(
        protected AdmService $admService
    )
    {}
    function store(admRequest $request): Adm
    {
        return $this->admService->store(AdmDTO::fromRequestValidated($request));
    }

    /**
     * @throws Exception
     */
    function login(admRequest $request): RedirectResponse | View
    {
        $this->admService->authenticate(AdmDTO::fromRequestValidated($request));
        return response()->redirectToRoute('index')->setStatusCode(Response::HTTP_CREATED);
    }

    function logout(): RedirectResponse
    {
        $this->admService->logout();
        return response()->redirectToRoute('home_page')->setStatusCode(Response::HTTP_OK);
    }

    function index(): View
    {
        $products = Product::paginate(5);
        return view('auth/index',compact('products'));
    }

    function users(): View | User
    {
        $users = Client::paginate(8);
        return view('auth/users/users',compact('users'));
    }

    function add_product(): View
    {
        return view('auth/product/create_product');
    }

    function edit_product($id): View | Product
    {
        $product = Product::find($id);
        return view('auth/product/edit_product',compact('product'));
    }
}

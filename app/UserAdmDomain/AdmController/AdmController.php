<?php

namespace App\UserAdmDomain\AdmController;

use App\Http\Controllers\Controller;
use App\Models\Adm;
use App\UserAdmDomain\AdmDTO\AdmDTO;
use App\UserAdmDomain\AdmService\AdmService;
use App\UserAdmDomain\Requests\admRequest;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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
    function login(admRequest $request): RedirectResponse
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
        return view('auth/index');
    }
}

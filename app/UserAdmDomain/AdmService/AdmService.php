<?php

namespace App\UserAdmDomain\AdmService;

use App\Models\Adm;
use App\UserAdmDomain\AdmDTO\AdmDTO;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdmService
{
    public function store(AdmDTO $admDTO): Adm
    {
        $adm = new Adm([
            'email' => $admDTO->email,
            'password' => $admDTO->password
        ]);

        $adm->save();

        return $adm;
    }

    /**
     * @throws Exception
     */
    public function authenticate(AdmDTO $admDTO): Authenticatable | JsonResponse
    {
        if (!Auth::attempt((array)$admDTO)){
            throw new HttpException(Response::HTTP_UNAUTHORIZED,'Erro: Usuario nao encontrado!!!');
        }
        $user = Adm::find(Auth::id());
        if ($user->isAdmin != 1){
            Auth::logout();
            throw new HttpException(Response::HTTP_FORBIDDEN,'Erro: Usuario nao encontrado!!!',);
        }
        request()->session()->regenerate();
        return Auth::user();
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}

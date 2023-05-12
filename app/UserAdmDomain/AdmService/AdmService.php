<?php

namespace App\UserAdmDomain\AdmService;

use App\Models\Adm;
use App\UserAdmDomain\AdmDTO\AdmDTO;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

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
    public function authenticate(AdmDTO $admDTO): ?Authenticatable
    {
        if (!Auth::attempt((array)$admDTO)){
            throw new Exception('Erro: Usuario nao encontrado!!!');
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

<?php

namespace App\UserDomain\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'address_delivery' => 'nullable', //TODO arrumar o endereço de entrega e de cobrança
            'address_billing' => 'nullable',
            'phone_number' => 'required'
        ];
    }
}

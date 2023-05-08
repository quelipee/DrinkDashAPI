<?php

namespace App\UserDomain\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'nullable',
            'email' => 'nullable',
            'password' => 'nullable',
            'address_delivery' => 'nullable',
            'address_billing' => 'nullable',
            'phone_number' => 'nullable'
        ];
    }
}

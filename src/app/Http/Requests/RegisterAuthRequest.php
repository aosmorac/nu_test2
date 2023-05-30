<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users',
            'phone'     => 'required|string|regex:/^[0-9]{7,15}$/',
            'password'  => 'required|string|min:6',
        ];
    }
}

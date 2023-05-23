<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'    => 'required|string|min:2',
            'email'   => 'required|email',
            'phone'   => 'required|string|regex:/^[0-9]{7,15}$/',
            'comment' => 'nullable|string|min:3|max:300',
        ];
    }
}

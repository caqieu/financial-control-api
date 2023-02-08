<?php

namespace App\Http\Requests;

use App\Rules\OnlyAlphaRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => 'required|string|max:255,min:3',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users,email',
                new OnlyAlphaRule()
            ],
            'password' => 'required|min:6|max:120'
        ];
    }
}

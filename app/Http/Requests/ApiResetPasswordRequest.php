<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest() == true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ];
    }
}

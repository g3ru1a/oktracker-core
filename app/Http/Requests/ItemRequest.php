<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'book_id' => 'required|numeric',
            'collection_id' => 'required|numeric',
            'vendor_id' => 'required|numeric',
            'price' => 'required|numeric',
            'bought_on' => 'required|string',
            'arrived' => 'boolean',
        ];
    }
}

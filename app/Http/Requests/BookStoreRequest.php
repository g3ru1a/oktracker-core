<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
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
            'title' => 'required|string',
            'isbn_10' => 'required|string',
            'isbn_13' => 'required|string',
            'cover_url' => 'string|nullable',
            'publish_date' => 'string|nullable',
            'pages' => 'numeric|nullable',
            'series_id' => 'string|nullable',
            'volume_number' => 'numeric|nullable',
            'is_oneshot' => 'string|nullable',
            'new' => 'boolean|nullable',
            'cover' => 'image|nullable'
        ];
    }
}

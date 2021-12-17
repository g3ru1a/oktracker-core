<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeriesStoreRequest extends FormRequest
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
            'language' => 'required|string',
            'cover_url' => 'string|nullable',
            'publisher' => 'string|nullable',
            'summary' => 'string|nullable',
            'authors' => 'string|nullable',
            'kind' => 'string|nullable',
            'contributions' => 'string|nullable',
            'cover' => 'image|nullable'
        ];
    }
}

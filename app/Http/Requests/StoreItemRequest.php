<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'part_number' => [
                'required', 'string', 'max:191',
                'unique:items,part_number'
            ],
            'description' => ['required', 'string', 'max:65000'],
            'price' => ['required', 'numeric', 'max:9999999999'],
            'satuan_brg' => ['required', 'string', 'max:65000'],
            'image' => [
                'nullable', 'file', 'mimes:png,jpg,jpeg',
                'max:5000'
            ]
        ];
    }
}

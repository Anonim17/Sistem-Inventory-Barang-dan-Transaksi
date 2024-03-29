<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UpdateItemRequest extends FormRequest
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
        $id = $this->segment(count($this->segments()));

        return [
            'part_number' => [
                'required', 'string', 'max:191',
                Rule::unique('items')->ignore($id),
            ],
            'description' => ['required', 'string', 'max:65000'],
             'satuan_brg' => ['required', 'string', 'max:65000'],
            'price' => ['required', 'numeric', 'max:9999999999'],
            'image' => [
                'nullable', 'file', 'mimes:png,jpg,jpeg',
                'max:5000'
            ],
           
        ];
    }
}

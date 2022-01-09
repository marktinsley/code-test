<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUpdateProduct extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ];
    }
}

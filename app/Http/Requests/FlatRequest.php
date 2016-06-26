<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FlatRequest extends Request
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
            'block' => 'required|max:15',
            'floor' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'view' => 'required'
        ];
    }
}

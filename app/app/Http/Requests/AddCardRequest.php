<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCardRequest extends FormRequest
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
            'number' => 'required|exists:cards,number|regex:/^[0-9]{13,16}$/',
            'cvv' => 'required|regex:/^[0-9]{3}$/',
            'expires-end' => 'required'
        ];
    }
}

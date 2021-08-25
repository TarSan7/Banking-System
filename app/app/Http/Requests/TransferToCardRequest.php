<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferToCardRequest extends FormRequest
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
            'numberFrom' => 'required',
            'numberTo' => 'required|exists:cards,number|regex:/^[0-9]{13,16}$/',
            'sum' => 'required',
            'comment' => 'regex:/^.{0,}$/'
        ];
    }
}

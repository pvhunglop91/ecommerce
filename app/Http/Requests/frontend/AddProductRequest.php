<?php

namespace App\Http\Requests\frontend;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'name'=>'required',
            'price'=>'required',
            'company_profile'=>'required',
            'image'=>'',
            'image.*' =>'|mimes:jpeg,jpg,png|max:2048'
        ];
    }

    public function messages()
    {
        return[
            'required' => ':attribute khong duoc de trong',
            'max'=>'khong duoc lon hon 1m',
            'mimes'=>'day khong phai la file anh'

        ];
    }
    public function attributes()
    {
        return [
            'name'=>'name',
            'price'=>'price',
            'company_profile'=>'company profile',
            'image'=>'image'

        ];
    }
}

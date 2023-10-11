<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
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
            'name'=>'required|max:20',
            'age'=>'required|integer|max:20',
        ];
    }
    public function messages()
    {
        return [
            'required'=>':attribute khong duoc de trong',
            'max'=>':attribute khong duoc qua :max ki tu',
        ];

    }
    public function attributes()
    {
        return[
            'name'=>'ten',
            'age'=>'tuoi',
        ];
    }
}

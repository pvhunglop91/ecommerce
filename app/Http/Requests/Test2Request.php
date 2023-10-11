<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Test2Request extends FormRequest
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
            'name'=>'required|min:5',
            'age'=>'required|integer|max:3',
        ];
    }
    public function messages()
    {
        return[
        'required'=>':attribute khong duoc de trong',
        'min'=>':attribute khong duoc nho hon :min',
        'max'=>':attribute khong duoc lon hon :max',
        ];
    }
    public function attributes()
    {
        return [
            'name'=>'title',
            'age'=>'tuoi',
        ];
    }
}

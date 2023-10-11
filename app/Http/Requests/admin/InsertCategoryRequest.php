<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class InsertCategoryRequest extends FormRequest
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
            'category_name'=>'required|max:255'
        ];
    }
    public function messages()
    {
        return [
            'required'=>':attribute khong duoc de trong'
        ];
    }
    public function attributes()
    {
        return [
            'category_name'=>'category name'
        ];
    }

}

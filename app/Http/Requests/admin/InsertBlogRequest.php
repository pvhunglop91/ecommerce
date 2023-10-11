<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class InsertBlogRequest extends FormRequest
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
            'title'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg,gif|max:2048',
            'description'=>'required',
            'content' => 'required'
        ];
    }
    public function messages()
        {

            return [
                'required'=>':attribute khong duoc de trong',
                'max'=>':attribute khong duoc lon hon 1mb',
                'mimes'=>':attribute ko phai la file anh'
            ];
        }
        public function attributes()
        {
            return[
                'title' => 'title',
                'image'=>'image',
                'description'=>'description',
                'content'=>'content'
            ];
        }
}

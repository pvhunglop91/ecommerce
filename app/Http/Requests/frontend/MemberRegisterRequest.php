<?php

namespace App\Http\Requests\frontend;

use Illuminate\Foundation\Http\FormRequest;

class MemberRegisterRequest extends FormRequest
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
            'email'=>'required',
            'password'=>'required',
            'phone'=>'required',

            'avatar'=>'mimes:jpeg,png,jpg,gif|max:2048',
            
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
                'email' => 'email',
                'name'=>'username',
                'phone'=>'phone',

                'password'=>'password'

            ];
        }
}

<?php

namespace App\Http\Requests\frontend;

use Illuminate\Foundation\Http\FormRequest;

class MemberLoginRequest extends FormRequest
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
            'email'=>'required',
            'password'=>'required',
            ];
        }
        public function messages()
        {

            return [
                'required'=>':attribute khong duoc de trong',

            ];
        }
        public function attributes()
        {
            return[
                'email' => 'email',
                'password'=>'password'

            ];
        }
}

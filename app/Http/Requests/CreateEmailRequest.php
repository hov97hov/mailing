<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmailRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:emails'],
            'host' => ['required'],
            'from' => ['required'],
            'header' => ['required'],
            'port' => ['required'],
            'encryption' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Էլփոստի դաշտը պարտադիր է:',
            'email.unique' => 'Նամակն արդեն վերցված է:',
            'host.required' => 'դաշտը պարտադիր է:',
            'from.required' => 'դաշտը պարտադիր է:',
            'header.required' => 'դաշտը պարտադիր է:',
            'port.required' => 'դաշտը պարտադիր է:',
            'encryption.required' => 'Էլփոստի դաշտը պարտադիր է:',
            'username.required' => 'դաշտը պարտադիր է:',
            'password.required' => 'դաշտը պարտադիր է:',
        ];
    }
}

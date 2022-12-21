<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'host' => ['required'],
            'from' =>  ['required'],
            'header' =>  ['required'],
            'port' =>  ['required'],
            'encryption' =>  ['required'],
            'username' =>  ['required'],
            'password' =>  ['required'],
        ];
    }

    public function messages()
    {
        return [
            'host.required' => 'Host դաշտը պարտադիր է:',
            'from.required' => 'From դաշտը պարտադիր է:',
            'port.required' => 'Port դաշտը պարտադիր է:',
            'encryption.required' => 'Encryption դաշտը պարտադիր է:',
            'username.required' => 'Username դաշտը պարտադիր է:',
            'password.required' => 'Password դաշտը պարտադիր է:',
        ];
    }
}

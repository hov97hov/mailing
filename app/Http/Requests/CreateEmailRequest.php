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
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Էլփոստի դաշտը պարտադիր է:',
            'email.unique' => 'Նամակն արդեն վերցված է:',
            'email' => 'Էլփոստի դաշտը պարտադիր է:',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Անվան դաշտը պարտադիր է:',
            'name' => 'Անվան դաշտը պարտադիր է:',
            'email.required' => 'Էլփոստի դաշտը պարտադիր է:',
            'email.unique' => 'Նամակն արդեն վերցված է:',
            'email' => 'Էլփոստի դաշտը պարտադիր է:',
            'password.required' => 'Գաղտնաբառի դաշտը պարտադիր է:',
            'password.confirmed' => 'Գաղտնաբառի հաստատումը չի համընկնում:',
            'password' => 'Գաղտնաբառի դաշտը պարտադիր է:',
            'password_confirmation.required' => 'Գաղտնաբառի դաշտը պարտադիր է:',
            'password_confirmation' => 'Գաղտնաբառի դաշտը պարտադիր է:',
        ];
    }
}

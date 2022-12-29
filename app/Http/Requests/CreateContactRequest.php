<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:contacts'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Անվան դաշտը պարտադիր է:',
            'name' => 'Անվան դաշտը պարտադիր է:',
            'email.required' => 'Էլփոստի դաշտը պարտադիր է:',
            'email.unique' => 'Էլ. փոստի արդեն գրանցված է:',
            'email' => 'Էլ. փոստի դաշտը պարտադիր է:',
        ];
    }
}

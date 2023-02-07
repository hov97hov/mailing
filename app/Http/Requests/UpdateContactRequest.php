<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
            'categoryId' => ['required'],
            'email' => ['required', 'email'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'դաշտը պարտադիր է լրացնել:',
            'name' => 'դաշտը պարտադիր է լրացնել:',
            'email.required' => 'դաշտը պարտադիր է լրացնել:',
            'email.unique' => 'Էլ․ փոստը արդեն գրանցված է',
            'email' => 'դաշտը պարտադիր է լրացնել:',
            'categoryId.required' => 'դաշտը պարտադիր է լրացնել:',
        ];
    }
}

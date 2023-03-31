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
            'name' => ['required', 'max:50'],
            'categoryId' => ['required'],
            'email' => ['required', 'email', 'unique:contacts', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'դաշտը պարտադիր է լրացնել:',
            'name.max' => 'Անունը չպետք է լինի 20 նիշից ավելի:',
            'name' => 'դաշտը պարտադիր է լրացնել:',
            'email.required' => 'դաշտը պարտադիր է լրացնել:',
            'email.unique' => 'Էլ․ փոստը արդեն գրանցված է',
            'email.regex' => 'Լրացնել միայն լատինատառ',
            'categoryId.required' => 'դաշտը պարտադիր է լրացնել:',
            'email' => 'Ճիշտ չեք գրել Էլ հասցեն'
        ];
    }
}

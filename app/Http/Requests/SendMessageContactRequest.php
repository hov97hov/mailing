<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageContactRequest extends FormRequest
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
            'subject' => 'required',
            'from' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Պարտադիր է լրացնել դաշտը',
            'from.required' => 'Պարտադիր է լրացնել դաշտը'
        ];
    }
}

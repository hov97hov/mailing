<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sendEmailImgLinkRequest extends FormRequest
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
            'from' => 'required',
            'categoryIds' => 'required',
            'subject' => 'required',
            'image' => 'required',
            'imgLink' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'from.required' => 'դաշտը պարտադիր է:',
            'categoryIds.required' => 'դաշտը պարտադիր է:',
            'subject.required' => 'դաշտը պարտադիր է:',
            'image.required' => 'դաշտը պարտադիր է:',
            'imgLink.required' => 'դաշտը պարտադիր է:',
        ];
    }
}

<?php

namespace PortedCheese\AjaxForms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormFieldCreateRequest extends FormRequest
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
            'title' => 'required|min:2',
            'exists' => 'nullable|required_without_all:name,type|exists:ajax_form_fields,id',
            'type' => 'nullable|required_without:exists',
            'name' => 'nullable|required_without:exists|min:4|unique:ajax_form_fields,name',
        ];
    }
}

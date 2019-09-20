<?php

namespace PortedCheese\AjaxForms\Http\Requests;

use App\AjaxFormField;
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
        return AjaxFormField::requestFormFieldCreate($this);
    }

    public function attributes()
    {
        return AjaxFormField::requestFormFieldCreate($this, true);
    }
}

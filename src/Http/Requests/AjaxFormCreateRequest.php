<?php

namespace PortedCheese\AjaxForms\Http\Requests;

use App\AjaxForm;
use Illuminate\Foundation\Http\FormRequest;

class AjaxFormCreateRequest extends FormRequest
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
        return AjaxForm::requestAjaxFormCreate($this);
    }

    public function attributes()
    {
        return AjaxForm::requestAjaxFormCreate($this, true);
    }
}

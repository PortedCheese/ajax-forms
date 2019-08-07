<?php

namespace PortedCheese\AjaxForms\Http\Requests;

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
        return [
            'title' => 'required|min:2',
            'name' => 'required|min:4|unique:ajax_forms,name',
            'email' => 'nullable|email',
        ];
    }

    public function attributes()
    {
        return [
            'title' => "Заголовок",
            'name' => "Имя формы",
            'email' => "E-mail",
        ];
    }
}

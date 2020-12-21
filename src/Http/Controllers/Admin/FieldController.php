<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Admin;

use App\AjaxForm;
use App\AjaxFormField;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    /**
     * Форма создания.
     *
     * @param AjaxForm $form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(AjaxForm $form)
    {
        return view("ajax-forms::admin.ajax-fields.create", [
            'types' => AjaxFormField::TYPES,
            'form' => $form,
            'available' => AjaxFormField::getForForm($form),
        ]);
    }

    /**
     * Сохранение.
     *
     * @param Request $request
     * @param AjaxForm $form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, AjaxForm $form)
    {
        $this->storeValidator($request->all());
        if (empty($request->get('exists'))) {
            $field = AjaxFormField::create($request->all());
        }
        else {
            $field = AjaxFormField::find($request->get('exists'));
        }

        $data = [
            'title' => $request->get('title'),
            'required' => $request->has('required') ? 1 : 0,
        ];
        $form->fields()->attach($field, $data);
        return redirect()
            ->route("admin.ajax-forms.show", ['ajax_form' => $form])
            ->with('success', 'Поле успешно добавлено');
    }

    /**
     * Валидация
     *
     * @param $data
     */
    public function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "min:2", "max:100"],
            "exists" => ["nullable", "required_without_all:name,type", "exists:ajax_form_fields,id"],
            "type" => ["nullable", "required_without:exists"],
            "name" => ["nullable", "required_without:exists", "min:4", "unique:ajax_form_fields,name"],
        ], [], [
            "title" => "Заголовок",
            "exists" => "Существующее поле",
            "type" => "Тип поля",
            "name" => "Имя поля",
        ])->validate();
    }

    /**
     * Редактирование поля.
     *
     * @param AjaxForm $form
     * @param AjaxFormField $field
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(AjaxForm $form, AjaxFormField $field)
    {
        $pivot = $field->forms()->find($form->id)->pivot;
        return view("ajax-forms::admin.ajax-fields.edit", [
            'form' => $form,
            'field' => $field,
            'pivot' => $pivot,
        ]);
    }

    /**
     * Обновляем заголовок у поля.
     *
     * @param Request $request
     * @param AjaxForm $form
     * @param AjaxFormField $field
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, AjaxForm $form, AjaxFormField $field)
    {
        $this->updateValidator($request->all());
        $data = [
            'title' => $request->get('title'),
            'required' => $request->has('required') ? 1 : 0,
        ];
        $form->fields()->updateExistingPivot($field->id, $data);
        
        return redirect()
            ->route('admin.ajax-forms.show', ['ajax_form' => $form])
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Валидация.
     *
     * @param array $data
     */
    protected function updateValidator(array $data)
    {
        Validator::make($data, [
            "title" => ["required", "min:2", "max:100"],
        ], [], [
            "title" => "Заголовок",
        ])->validate();
    }

    /**
     * Открепить поле от формы.
     *
     * @param AjaxForm $form
     * @param AjaxFormField $field
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function detach(AjaxForm $form, AjaxFormField $field)
    {
        if ($form->submissions->count()) {
            return redirect()
                ->route("admin.ajax-forms.show", ['ajax_form' => $form])
                ->with('danger', 'Невозможно удалить поле, у формы есть сообщения.');
        }
        $form->fields()->detach($field);
        $field->checkFormsOnDetach();
        return redirect()
            ->route("admin.ajax-forms.show", ['ajax_form' => $form])
            ->with('success', 'Поле успешно откреплено');
    }
}

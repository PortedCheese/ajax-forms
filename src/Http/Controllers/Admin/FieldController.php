<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Admin;

use App\AjaxForm;
use App\AjaxFormField;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PortedCheese\AjaxForms\Http\Requests\FormFieldCreateRequest;
use PortedCheese\AjaxForms\Http\Requests\FormFieldUpdateRequest;

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
     * @param FormFieldCreateRequest $request
     * @param AjaxForm $form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormFieldCreateRequest $request, AjaxForm $form)
    {
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
    public function update(FormFieldUpdateRequest $request, AjaxForm $form, AjaxFormField $field)
    {
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
     * Открепить поле от формы.
     *
     * @param AjaxForm $form
     * @param AjaxFormField $field
     * @return \Illuminate\Http\RedirectResponse
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

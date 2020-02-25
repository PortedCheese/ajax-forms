<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Admin;

use App\AjaxForm;
use App\AjaxFormSubmission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormCreateRequest;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormUpdateRequest;
use PortedCheese\AjaxForms\Http\Services\FilterFields;

class FormController extends Controller
{
    const PAGER = 20;

    protected $filterFields;

    public function __construct()
    {
        parent::__construct();
        $this->filterFields = new FilterFields();
    }

    /**
     * Список всех форм.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $forms = AjaxForm::all();
        return view("ajax-forms::admin.ajax-forms.index", [
            'forms' => $forms,
        ]);
    }

    /**
     * Добавление новой формы.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("ajax-forms::admin.ajax-forms.create");
    }

    /**
     * Сохранение формы.
     *
     * @param AjaxFormCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AjaxFormCreateRequest $request)
    {
        AjaxForm::create($request->all());
        return redirect()
            ->route('admin.ajax-forms.index')
            ->with('success', 'Форма успешно создана');
    }

    /**
     * Просмотр формы.
     *
     * @param AjaxForm $ajax_form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(AjaxForm $ajax_form)
    {
        return view("ajax-forms::admin.ajax-forms.show", [
            'form' => $ajax_form,
        ]);
    }

    /**
     * Редактирование формы.
     *
     * @param AjaxForm $ajax_form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(AjaxForm $ajax_form)
    {
        return view("ajax-forms::admin.ajax-forms.edit", [
            'form' => $ajax_form,
        ]);
    }

    /**
     * Обновление формы.
     *
     * @param AjaxFormUpdateRequest $request
     * @param AjaxForm $ajax_form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AjaxFormUpdateRequest $request, AjaxForm $ajax_form)
    {
        $ajax_form->update($request->all());
        return redirect()
            ->back()
            ->with('success', 'Успешно обновленно');
    }

    /**
     * Удаление формы.
     *
     * @param AjaxForm $ajax_form
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(AjaxForm $ajax_form)
    {
        if ($ajax_form->submissions->count()) {
            return redirect()
                ->route('admin.ajax-forms.index')
                ->with('danger', 'Невозможно удалить форму у которой есть сообщения');
        }
        $ajax_form->delete();
        return redirect()
            ->route('admin.ajax-forms.index')
            ->with('success', 'Успешно удалено');
    }
}

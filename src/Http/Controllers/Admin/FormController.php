<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Admin;

use App\AjaxForm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());
        $form = AjaxForm::create($request->all());
        return redirect()
            ->route('admin.ajax-forms.show', ["ajax_form" => $form])
            ->with('success', 'Форма успешно создана');
    }

    /**
     * Валидация.
     *
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "min:2", "max:100"],
            'name' => ["required", "min:2", "max:100", "unique:ajax_forms,name"],
            'email' => ["required", "email", "max:250"],
        ], [], [
            'title' => "Заголовок",
            'name' => "Имя формы",
            'email' => "E-mail",
        ])->validate();
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
     * @param Request $request
     * @param AjaxForm $ajax_form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, AjaxForm $ajax_form)
    {
        $this->updateValidator($request->all());
        $ajax_form->update($request->all());
        return redirect()
            ->back()
            ->with('success', 'Успешно обновленно');
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
            "email" => ["required", "email", "max:250"],
        ], [], [
            'title' => "Заголовок",
            'email' => "E-mail",
        ])->validate();
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

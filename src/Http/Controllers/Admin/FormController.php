<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormCreateRequest;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormUpdateRequest;
use PortedCheese\AjaxForms\Http\Services\FilterFields;
use PortedCheese\AjaxForms\Models\AjaxForm;
use PortedCheese\AjaxForms\Models\AjaxFormSubmission;

class FormController extends Controller
{
    const PAGER = 20;

    protected $filterFields;

    public function __construct()
    {
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

    /**
     * Страница формы для просмотра сообщений.
     *
     * @param Request $request
     * @param AjaxForm $form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submissions(Request $request, AjaxForm $form)
    {
        $headers = $form->getHeaders();
        $ids = $this->filterFields->getIds($form, $request, $headers);
        $collection = AjaxFormSubmission::whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(self::PAGER)
            ->appends($request->input());

        $submissions = [];
        foreach ($collection as $submission) {
            $fields = [];
            foreach ($submission->values as $value) {
                $field = $value->field;
                $fieldId = $field->id;
                if ($field->type != 'file') {
                    $fields[$fieldId] = [
                        'value' => !empty($value->value) ? $value->value : $value->long_value,
                        'type' => $field->type,
                    ];
                }
                else {
                    $fields[$fieldId] = [
                        'value' => url()->route('admin.ajax-forms.submission.download', ['submission' => $submission]),
                        'type' => $field->type,
                    ];
                }
            }
            $submissions[] = (object) [
                'model' => $submission,
                'fields' => $fields,
                'author' => empty($submission->author) ? "Гость" : $submission->author->email,
            ];
        }

        return view("ajax-forms::admin.ajax-forms.submissions", [
            'form' => $form,
            'headers' => $headers,
            'submissions' => $submissions,
            'query' => $request->query,
            'per' => self::PAGER,
            'page' => $request->query->get('page', 1) - 1,
            'collection' => $collection,
        ]);
    }

    public function download(AjaxFormSubmission $submission)
    {
        $path = FALSE;
        foreach ($submission->values as $value) {
            $field = $value->field;
            if ($field->type == 'file') {
                $path = $value->value;
            }
        }
        if (!$path) {
            return redirect()
                ->back()
                ->with('danger', 'Файл не найден');
        }
        return response()
            ->download(Storage::path($path));
    }

    /**
     * Удалить сообщение.
     *
     * @param AjaxFormSubmission $submission
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroySubmission(AjaxFormSubmission $submission)
    {
        $form = $submission->form;
        $submission->delete();
        return redirect()
            ->route('admin.ajax-forms.submissions', ['form' => $form])
            ->with('success', 'Сообщение удалено.');
    }
}

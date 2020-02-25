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

class SubmissionController extends Controller
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
        return view("ajax-forms::admin.ajax-submissions.index", [
            'forms' => $forms,
        ]);
    }

    /**
     * Страница формы для просмотра сообщений.
     *
     * @param Request $request
     * @param AjaxForm $form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, AjaxForm $form)
    {
        $this->authorize("view", AjaxFormSubmission::class);

        $headers = $form->getHeaders();
        $ids = $this->filterFields->getIds($form, $request, $headers);
        $collection = AjaxFormSubmission::query()
            ->whereIn('id', $ids)
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

    /**
     * Скачать файл.
     *
     * @param AjaxFormSubmission $submission
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function download(AjaxFormSubmission $submission)
    {
        $this->authorize("view", $submission);
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
    public function destroy(AjaxFormSubmission $submission)
    {
        $this->authorize("delete", $submission);
        $form = $submission->form;
        $submission->delete();
        return redirect()
            ->route('admin.ajax-forms.submissions.show', ['form' => $form])
            ->with('success', 'Сообщение удалено.');
    }
}

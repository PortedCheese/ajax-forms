<?php

namespace PortedCheese\AjaxForms\Helpers;

use App\AjaxForm;
use App\AjaxFormField;
use App\AjaxFormSubmission;
use App\AjaxFormValue;
use Illuminate\Http\Request;
use PortedCheese\AjaxForms\Events\CreateNewSubmission;

class FormSubmissionActionsManager
{
    /**
     * Добавить отправление.
     *
     * @param AjaxForm $form
     * @param Request $request
     */
    public function createSubmission(AjaxForm $form, Request $request)
    {
        $fields = $this->getFormFields($form, $request);
        $submission = $this->createNewSubmission($form);
        $this->addFieldsToSubmission($submission, $fields);
        event(new CreateNewSubmission($submission));
    }

    /**
     * Подготовить поля для вывода.
     *
     * @param AjaxFormSubmission $submission
     * @return array
     */
    public function prepareFieldsForRender(AjaxFormSubmission $submission)
    {
        $fields = [];
        foreach ($submission->values as $value) {
            $field = $value->field;
            $fieldId = $field->id;
            if ($field->type != 'file') {
                $text = !empty($value->value) ? $value->value : $value->long_value;
                $fields[$fieldId] = [
                    'value' => $text,
                    'type' => $field->type,
                    "render" => $text,
                ];
            }
            else {
                $url = url()->route('admin.ajax-forms.submissions.download', ['submission' => $submission]);
                $fields[$fieldId] = [
                    'value' => $url,
                    'type' => $field->type,
                    "render" => "<a href='{$url}'>Скачать</a>"
                ];
            }
        }
        return $fields;
    }

    /**
     * Создать отправление.
     *
     * @param AjaxForm $form
     * @return \Illuminate\Database\Eloquent\Model|AjaxFormSubmission
     */
    protected function createNewSubmission(AjaxForm $form)
    {
        return $form->submissions()->create([]);
    }

    /**
     * Заполнить поля.
     *
     * @param AjaxFormSubmission $submission
     * @param array $fields
     */
    protected function addFieldsToSubmission(AjaxFormSubmission $submission, array $fields)
    {
        foreach ($fields as $item) {
            $field = $item["field"];
            /**
             * @var AjaxFormField
             */
            $value = $item["value"];
            $long = null;
            $str = null;
            switch ($field->type) {
                case "longText":
                    $long = $value;
                    break;

                case "file":
                    $path = $value->store("submission");
                    $str = $path;
                    break;

                default:
                    $str = $value;
                    break;
            }
            AjaxFormValue::create([
                "submission_id" => $submission->id,
                "field_id" => $field->id,
                "long_value" => $long,
                "value" => $str,
            ]);
        }
    }

    /**
     * Получить поля формы.
     *
     * @param AjaxForm $form
     * @param Request $request
     * @return array
     */
    protected function getFormFields(AjaxForm $form, Request $request)
    {
        $fields = [];
        foreach ($form->fields as $field) {
            if ($request->has($field->name)) {
                if ($field->type == 'file') {
                    if (!$request->hasFile($field->name)) {
                        continue;
                    }
                    $value = $request
                        ->file($field->name);
                }
                else {
                    $value = $request->get($field->name);
                }
                $fields[] = [
                    'field' => $field,
                    'value' => $value,
                ];
            }
        }
        return $fields;
    }
}
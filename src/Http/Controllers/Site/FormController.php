<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PortedCheese\AjaxForms\Models\AjaxForm;

class FormController extends Controller
{
    public function __construct()
    {
        $this->result = [
            'success' => FALSE,
            'messages' => [],
            'userInputs' => [],
        ];
    }

    /**
     * Отправка формы.
     *
     * @param Request $request
     * @param $form
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request, $form)
    {
        // Ищем нужную форму.
        try {
            $form = AjaxForm::where('name', $form)->firstOrFail();
        }
        catch (\Exception $e) {
            $this->result['messages'][] = "Форма не найдена";
            return response()
                ->json($this->prepareMessages());
        }

        $formData = $request->all();
        // TODO: change validation func.
        $this->validateForm($formData);
        if (!$this->result['success']) {
            return response()
                ->json($this->prepareMessages());
        }
        $form->makeSubmission($this->result['userInputs']);
        $this->result['messages'][] = $form->success_message;
        return response()
            ->json($this->prepareMessages());
    }

    private function prepareMessages()
    {
        $this->result['messages'] = view("ajax-forms::site.messages", [
            'result' => $this->result,
        ])->render();
        return $this->result;
    }

    /**
     * Валидация формы.
     *
     * @param $formData
     */
    private function validateForm($formData)
    {
        if (
            empty($formData['input']) ||
            empty($formData['elements'])
        ) {
            $this->result['messages'][] = "Недостаточно параметров.";
            return;
        }
        parse_str($formData['input'], $userInputs);
        foreach ($formData['elements'] as $element) {
            if (
                !empty($element['required']) &&
                empty($userInputs[$element['name']])
            ) {
                $this->result['messages'][] = "Поле {$element['title']} обязательно для заполнения";
                continue;
            }
        }

        if (empty($this->result['messages'])) {
            $this->result['success'] = TRUE;
            $this->result['userInputs'] = $userInputs;
        }
    }
}

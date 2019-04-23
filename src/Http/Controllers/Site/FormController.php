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

        // TODO: change validation func.
        $this->validateForm($request);
        if (!$this->result['success']) {
            return response()
                ->json($this->prepareMessages());
        }
        try {
            $form->makeSubmission($request);
            $this->result['messages'][] = $form->success_message;
        }
        catch (\Exception $e) {
            $this->result['messages'][] = $form->fail_message;
            $this->result['success'] = FALSE;
        }
        return response()
            ->json($this->prepareMessages());
    }

    /**
     * Вывод сообщений в форму.
     *
     * @return array
     * @throws \Throwable
     */
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
    private function validateForm(Request $request)
    {
        // Должно быть описание элементов.
        if (!$request->has('jsonElements')) {
            $this->result['messages'][] = "Недостаточно параметров.";
            return;
        }
        // Пробуем получить описание элементов.
        try {
            $decoded = json_decode($request->get('jsonElements'), TRUE);
        }
        catch (\Exception $e) {
            $this->result['messages'][] = "Невозможно обработать данные.";
            return;
        }
        // Обходим элементы.
        foreach ($decoded as $element) {
            if (!empty($element['required']) && empty($request->get($element['name']))) {
                $this->result['messages'][] = "Поле {$element['title']} обязательно для заполнения";
                continue;
            }
        }
        // Если нет ошибок, то все хорошо.
        if (empty($this->result['messages'])) {
            $this->result['success'] = TRUE;
        }
    }
}

<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Site;

use App\AjaxForm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    protected $result;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->result = [
            'success' => false,
            'messages' => [],
        ];
    }

    /**
     * Отправка формы.
     * 
     * @param Request $request
     * @param $form
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function submit(Request $request, $form)
    {
        // Ищем нужную форму.
        try {
            $form = AjaxForm::query()
                ->where('name', $form)
                ->firstOrFail();
        }
        catch (\Exception $e) {
            $this->result['messages'][] = "Форма не найдена";
            return response()
                ->json($this->prepareMessages());
        }
        $rules = [
            'geo_check' => "hidden_captcha",
        ];
        $messages = [
            'geo_check.hidden_captcha' => "Ошибка заполнения",
        ];
        if (siteconf()->get("ajax-forms", "privacyPolicy")) {
            $rules['privacy_policy'] = "accepted";
            $messages['privacy_policy.accepted'] = "Требуется согласие с политикой конфиденциальности";
        }
        if (siteconf()->get("ajax-forms", "recaptchaEnabled") && ! Auth::check()) {
            $rules["g-recaptcha-response"] = 'required|google_captcha';
            $messages['g-recaptcha-response.required'] = "Подтвердите что Вы не робот";
            $messages['g-recaptcha-response.hidden_captcha'] = "Ошибка подтверждения";
        }
        foreach ($form->fields as $field) {
            $pivot = $field->pivot;
            if (! $pivot->required) {
                continue;
            }
            $rules[$field->name] = "required";
            $messages[$field->name . ".required"] = "Поле {$pivot->title} обязательно для заполнения";
        }
        if (! empty($rules)) {
            Validator::make($request->all(), $rules, $messages)
                ->validate();
            $this->result['success'] = true;
        }
        else {
            $this->result['success'] = true;
        }
        try {
            $form->makeSubmission($request);
            $this->result['messages'][] = $form->success_message;
        }
        catch (\Exception $e) {
            $this->result['messages'][] = $form->fail_message;
            $this->result['success'] = false;
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

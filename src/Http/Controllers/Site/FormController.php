<?php

namespace PortedCheese\AjaxForms\Http\Controllers\Site;

use App\AjaxForm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PortedCheese\AjaxForms\Facades\FormSubmissionActions;

class FormController extends Controller
{

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
            return response()
                ->json($this->prepareMessages(["Форма не найдена"]));
        }
        /**
         * @var AjaxForm $form
         */

        $this->makeFormValidation($form, $request->all());

        try {
            FormSubmissionActions::createSubmission($form, $request);
            return response()
                ->json($this->prepareMessages([$form->success_message], true));
        }
        catch (\Exception $e) {
            return response()
                ->json($this->prepareMessages([$form->fail_message]));
        }
    }

    /**
     * Валидация формы.
     *
     * @param AjaxForm $form
     * @param array $data
     */
    protected function makeFormValidation(AjaxForm $form, array $data)
    {
        $rules = [
            'geo_check' => "hidden_captcha",
        ];
        $messages = [
            'geo_check.hidden_captcha' => "Ошибка заполнения",
        ];

        if (config("ajax-forms.privacyPolicy", true)) {
            $rules['privacy_policy'] = "accepted";
            $messages['privacy_policy.accepted'] = "Требуется согласие с политикой конфиденциальности";
        }

        if (config("ajax-forms.recaptchaEnabled", false) && ! Auth::check()) {
            $rules["g-recaptcha-response"] = 'required|google_captcha';
            $messages['g-recaptcha-response.required'] = "Подтвердите что Вы не робот";
            $messages['g-recaptcha-response.hidden_captcha'] = "Ошибка подтверждения";
        }

        if (config("ajax-forms.smartCaptchaEnabled", false) && ! Auth::check()) {
            $rules["smart-token"] = 'required|smart_captcha';
            $messages['smart-token.required'] = "Подтвердите что Вы не робот";
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
            Validator::make($data, $rules, $messages)
                ->validate();
        }
    }

    /**
     * Вывод сообщений в форму.
     *
     * @param array $messages
     * @param bool $success
     * @return array
     * @throws \Throwable
     */
    protected function prepareMessages($messages = [], $success = false)
    {
        $this->result['messages'] = view("ajax-forms::site.messages", [
            'result' => [
                "success" => $success,
                "messages" => $messages,
            ],
        ])->render();

        return $this->result;
    }
}

<?php

namespace PortedCheese\AjaxForms\Notifications;

use App\AjaxForm;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use PortedCheese\AjaxForms\Facades\FormSubmissionActions;

class AjaxFormSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     *
     * AjaxFormSubmissionNotification constructor.
     * @param $submission
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $form = $this->submission->form;
        /**
         * @var AjaxForm $form
         */
        $fields = FormSubmissionActions::prepareFieldsForRender($this->submission);
        $headers = $form->getHeaders();
        return (new MailMessage)
                    ->subject("Отправка формы {$form->title}")
                    ->markdown("ajax-forms::notifications.new-submit", [
                        "submission" => $this->submission,
                        "fields" => $fields,
                        "headers" => $headers,
                        "url" => route('admin.ajax-forms.submissions.show', ['form' => $form]),
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace PortedCheese\AjaxForms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class AjaxFormSubmissionNotification extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     *
     * @return void
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
        return (new MailMessage)
                    ->subject("Отправка формы {$form->title}")
                    ->greeting('Здравствуйте!')
                    ->line('На сайте было зарегистрировано обращение.')
                    ->action(
                        'Обращения',
                        route('admin.ajax-forms.submissions.show', ['form' => $form])
                    );
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

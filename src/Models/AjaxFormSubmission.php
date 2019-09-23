<?php

namespace PortedCheese\AjaxForms\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use PortedCheese\AjaxForms\Notifications\AjaxFormSubmissionNotification;
use App\AjaxFormValue;
use App\AjaxForm;

class AjaxFormSubmission extends Model
{
    use Notifiable;

    protected $fillable = [
        'user_id',
        'form_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (\App\AjaxFormSubmission $submission) {
            $submission->notify($submission->getNotifyClass($submission));
        });

        static::deleting(function (\App\AjaxFormSubmission $submission) {
            foreach ($submission->values as $value) {
                $value->delete();
            }
        });
    }

    /**
     * Уведомление.
     *
     * @param \App\AjaxFormSubmission $submission
     * @return AjaxFormSubmissionNotification
     */
    public function getNotifyClass(\App\AjaxFormSubmission $submission)
    {
        return new AjaxFormSubmissionNotification($submission);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->form->email;
    }

    /**
     * У сабмита есть много значений.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AjaxFormValue::class, 'submission_id');
    }

    /**
     * У сабмита может быть автор.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Принадлежит к форме.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(AjaxForm::class);
    }

    /**
     * Изменить временную зону.
     *
     * @param $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }

    /**
     * Создать сабмит и значения для формы.
     *
     * @param AjaxForm $form
     * @param array $fields
     */
    public static function createForFormByFields(AjaxForm $form, array $fields)
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
        }
        else {
            $user = NULL;
        }
        $submission = \App\AjaxFormSubmission::create([
            'user_id' => $user,
            'form_id' => $form->id,
        ]);
        foreach ($fields as $fieldData) {
            AjaxFormValue::createForSubmissionByFieldValues(
                $submission,
                $fieldData['field'],
                $fieldData['value']
            );
        }
    }
}

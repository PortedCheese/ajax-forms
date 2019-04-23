<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use PortedCheese\AjaxForms\Notifications\AjaxFormSubmissionNotification;

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

        static::created(function ($submission) {
            $submission->notify(new AjaxFormSubmissionNotification($submission));
        });

        static::deleting(function ($submission) {
            foreach ($submission->values as $value) {
                $value->delete();
            }
        });
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
        return $this->hasMany("PortedCheese\AjaxForms\Models\AjaxFormValue", 'submission_id');
    }

    /**
     * У сабмита может быть автор.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo("App\User", 'user_id');
    }

    /**
     * Принадлежит к форме.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo("PortedCheese\AjaxForms\Models\AjaxForm");
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
        $submission = AjaxFormSubmission::create([
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

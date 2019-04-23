<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AjaxFormSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'form_id',
    ];

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

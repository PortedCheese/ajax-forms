<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;

class AjaxFormValue extends Model
{
    protected $fillable = [
        'submission_id',
        'value',
        'long_value',
        'field_id',
    ];

    /**
     * Значение относится к конкретному сабмиту.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission()
    {
        return $this->belongsTo("PortedCheese\AjaxForms\Models\AjaxFormSubmission", 'submission_id');
    }

    /**
     * Значение относится к конкретному полю.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function field()
    {
        return $this->belongsTo("PortedCheese\AjaxForms\Models\AjaxFormField", 'field_id');
    }

    /**
     * Создать значения полей для сабмита.
     *
     * @param AjaxFormSubmission $submission
     * @param AjaxFormField $field
     * @param $value
     */
    public static function createForSubmissionByFieldValues(
        AjaxFormSubmission $submission,
        AjaxFormField $field,
        $value
    ) {
        $long = NULL;
        $str = NULL;
        if ($field->type == 'longText') {
            $long = $value;
        }
        else {
            $str = $value;
        }
        AjaxFormValue::create([
            'submission_id' => $submission->id,
            'field_id' => $field->id,
            'long_value' => $long,
            'value' => $str,
        ]);
    }
}

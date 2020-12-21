<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\AjaxFormSubmission;
use App\AjaxFormField;

class AjaxFormValue extends Model
{
    protected $fillable = [
        'submission_id',
        'value',
        'long_value',
        'field_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($value) {
            $field = $value->field;
            if ($field->type == 'file') {
                $path = $value->value;
                Storage::delete($path);
            }
        });
    }

    /**
     * Значение относится к конкретному сабмиту.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission()
    {
        return $this->belongsTo(AjaxFormSubmission::class, 'submission_id');
    }

    /**
     * Значение относится к конкретному полю.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function field()
    {
        return $this->belongsTo(AjaxFormField::class, 'field_id');
    }
}

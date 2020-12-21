<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use App\AjaxFormSubmission;
use App\AjaxFormField;

class AjaxForm extends Model
{
    protected $fillable = [
        'name',
        'title',
        'success_message',
        'fail_message',
        'email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (\App\AjaxForm $form) {
            foreach ($form->fields as $field) {
                /**
                 * @var AjaxFormField $field
                 */
                $form->fields()->detach($field);
                $field->checkFormsOnDetach();
            }
        });
    }

    /**
     * Можеть быть много сабмитов.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(AjaxFormSubmission::class, 'form_id');
    }

    /**
     * У формы может быть много полей
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->belongsToMany(AjaxFormField::class)
            ->withPivot('title', 'required')
            ->withTimestamps();
    }

    /**
     * Подгружать по name.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }


    /**
     * Заголовки таблицы для вывода.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        foreach ($this->fields as $field) {
            $headers[] = (object) [
                'title' => $field->pivot->title,
                'id' => $field->id,
                'name' => $field->name,
                'type' => $field->type,
            ];
        }
        return $headers;
    }
}

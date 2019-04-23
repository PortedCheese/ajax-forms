<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

        static::deleting(function ($form) {
            foreach ($form->fields as $field) {
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
        return $this->hasMany("PortedCheese\AjaxForms\Models\AjaxFormSubmission", 'form_id');
    }

    /**
     * У формы может быть много полей
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->belongsToMany('PortedCheese\AjaxForms\Models\AjaxFormField')
            ->withPivot('title')
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
     * Создать сабмит.
     *
     * @param $userInput
     */
    public function makeSubmission($userInput)
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if (!empty($userInput[$field->name])) {
                $fields[] = [
                    'field' => $field,
                    'value' => $userInput[$field->name],
                ];
            }
        }
        AjaxFormSubmission::createForFormByFields($this, $fields);
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

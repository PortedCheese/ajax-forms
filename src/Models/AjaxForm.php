<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * Создать сабмит.
     *
     * @param $userInput
     */
    public function makeSubmission(Request $request)
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($request->has($field->name)) {
                if ($field->type == 'file') {
                    if (!$request->hasFile($field->name)) {
                        continue;
                    }
                    $value = $request
                        ->file($field->name);
                }
                else {
                    $value = $request->get($field->name);
                }
                $fields[] = [
                    'field' => $field,
                    'value' => $value,
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

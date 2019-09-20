<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\AjaxFormSubmission;
use App\AjaxFormField;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormCreateRequest;
use PortedCheese\AjaxForms\Http\Requests\AjaxFormUpdateRequest;

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
     * Валидация создания формы.
     *
     * @param AjaxFormCreateRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestAjaxFormCreate(AjaxFormCreateRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                'title' => "Заголовок",
                'name' => "Имя формы",
                'email' => "E-mail",
            ];
        }
        else {
            return [
                'title' => 'required|min:2',
                'name' => 'required|min:4|unique:ajax_forms,name',
                'email' => 'nullable|email',
            ];
        }
    }

    /**
     * Обновление формы.
     * @param AjaxFormUpdateRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestAjaxFormUpdate(AjaxFormUpdateRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                'title' => "Заголовок",
                'email' => "E-mail",
            ];
        }
        else {
            return [
                'title' => 'required|min:2',
                'email' => 'nullable|email',
            ];
        }
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

<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;
use App\AjaxForm;
use App\AjaxFormValue;
use PortedCheese\AjaxForms\Http\Requests\FormFieldCreateRequest;
use PortedCheese\AjaxForms\Http\Requests\FormFieldUpdateRequest;

class AjaxFormField extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    const TYPES = [
        'text' => 'Текст',
        'longText' => "Длинный текст",
        'file' => "Файл",
    ];

    /**
     * Поле может быть привязанно к нескольким формам с разными заголовками.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function forms()
    {
        return $this->belongsToMany(AjaxForm::class)
            ->withPivot('title', 'required')
            ->withTimestamps();
    }

    /**
     * У поля могут быть значения.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AjaxFormValue::class, 'field_id');
    }

    /**
     * Валидация создания поля формы.
     *
     * @param FormFieldCreateRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestFormFieldCreate(FormFieldCreateRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                "title" => "Заголовок",
                "exists" => "Существующее поле",
                "type" => "Тип поля",
                "name" => "Имя поля",
            ];
        }
        else {
            return [
                'title' => 'required|min:2',
                'exists' => 'nullable|required_without_all:name,type|exists:ajax_form_fields,id',
                'type' => 'nullable|required_without:exists',
                'name' => 'nullable|required_without:exists|min:4|unique:ajax_form_fields,name',
            ];
        }
    }

    public static function requestFormFieldUpdate(FormFieldUpdateRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                "title" => "Заголовок",
            ];
        }
        else {
            return [
                'title' => 'required|min:2',
            ];
        }
    }

    /**
     * Если нет форм, удаляем поле.
     *
     * @throws \Exception
     */
    public function checkFormsOnDetach()
    {
        if (!$this->forms->count()) {
            $this->delete();
        }
    }

    /**
     * Получить поля, которые еще не добавленны к форме.
     *
     * @param AjaxForm $form
     * @return mixed
     */
    public static function getForForm(AjaxForm $form)
    {
        $ids = [];
        foreach ($form->fields as $field) {
            $ids[] = $field->id;
        }
        return \App\AjaxFormField::query()->whereNotIn('id', $ids)->get();
    }
}

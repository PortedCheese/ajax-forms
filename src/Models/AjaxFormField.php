<?php

namespace PortedCheese\AjaxForms\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany('PortedCheese\AjaxForms\Models\AjaxForm')
            ->withPivot('title')
            ->withTimestamps();
    }

    /**
     * У поля могут быть значения.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany("PortedCheese\AjaxForms\Models\AjaxFormValue", 'field_id');
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
        return AjaxFormField::whereNotIn('id', $ids)->get();
    }
}

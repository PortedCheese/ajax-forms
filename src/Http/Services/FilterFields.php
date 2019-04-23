<?php

namespace PortedCheese\AjaxForms\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PortedCheese\AjaxForms\Models\AjaxForm;
use PortedCheese\AjaxForms\Models\AjaxFormValue;

class FilterFields
{
    protected $query;

    public function __construct()
    {
        $this->query = DB::table('ajax_form_values as values')
            ->join('ajax_form_submissions as submissions', 'values.submission_id', '=', 'submissions.id')
            ->leftJoin('users', 'submissions.user_id', '=', 'users.id')
            ->select(
                'values.id as value_id',
                'values.field_id as field_id',
                'submissions.id as submission_id',
                'submissions.created_at as created_at'
            );
        $this->fields = [];
    }

    /**
     * Получить id сабмитов.
     *
     * @param AjaxForm $form
     * @param Request $request
     * @param $headers
     * @return array
     */
    public function getIds(AjaxForm $form, Request $request, $headers)
    {
        $this->query->where('submissions.form_id', $form->id);
        $this->getFieldSearch($headers, $request);
        $this->expandQuery();
        return $this->result();
    }

    /**
     * Добавить условия к запросу.
     */
    private function expandQuery()
    {
        foreach ($this->fields as $info) {
            switch ($info->type) {
                case 'like':
                    $this->expandLike($info->value, $info->key);
                    break;

                case 'moreThan':
                    $this->expandMoreThan($info->value, $info->key);
                    break;

                case 'lessThan':
                    $this->expandLessThan($info->value, $info->key);
                    break;
            }
        }
    }

    /**
     * Задать значения для поиска.
     *
     * @param $headers
     */
    private function getFieldSearch($headers, $request)
    {
        $queryValues = $request->all();
        foreach ($headers as $header) {
            $name = $header->name;
            if (!empty($queryValues[$name])) {
                $this->fields[] =  (object) [
                    'value' => trim($queryValues[$name]),
                    'key' => $header->type == 'longText' ? "values.long_value" : "values.value",
                    'type' => 'like',
                ];
            }
        }
        if (!empty($queryValues['author'])) {
            $this->fields[] = (object) [
                'value' => trim($queryValues['author']),
                'key' => 'users.email',
                'type' => 'like',
            ];
        }
        if (!empty($queryValues['from'])) {
            $this->fields[] = (object) [
                'value' => date("Y-m-d", strtotime($queryValues['from'])),
                'key' => 'submissions.created_at',
                'type' => 'moreThan',
            ];
        }
        if (!empty($queryValues['to'])) {
            $this->fields[] = (object) [
                'value' => date("Y-m-d", strtotime("+ 1 day", strtotime($queryValues['to']))),
                'key' => 'submissions.created_at',
                'type' => 'lessThan',
            ];
        }
    }

    /**
     * Получить результат.
     *
     * @return array
     */
    private function result()
    {
        $this->query->orderBy('submissions.created_at', 'desc');
        $this->query->groupBy('submissions.id');
        $result = $this->query->get();
        $ids = [];
        foreach ($result as $item) {
            $ids[] = $item->submission_id;
        }
        return $ids;
    }

    /**
     * Checkbox.
     */
    private function expandChecked($value, $field)
    {
        if (!empty($value)) {
            $this->query
                ->where($field, '=', 1);
        }
    }

    /**
     * Like operation.
     */
    private function expandLike($value, $field)
    {
        if (!empty($value)) {
            $this->query
                ->where($field, 'like', "%$value%");
        }
    }

    /**
     * In operator.
     */
    private function expandIn($value, $field)
    {
        if (!empty($value) && is_array($value)) {
            $this->query
                ->whereIn($field, $value);
        }
    }

    /**
     * Оператор больше или равно.
     */
    private function expandMoreThan($value, $field)
    {
        if (!empty($value)) {
            $this->query
                ->where($field, '>=', $value);
        }
    }

    /**
     * Оператор меньше или равно.
     */
    private function expandLessThan($value, $field)
    {
        if (!empty($value)) {
            $this->query
                ->where($field, '<=', $value);
        }
    }
}
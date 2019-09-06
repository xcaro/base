<?php

namespace Si6\Base\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HasCriteria
{
    /**
     * @param  Builder  $query
     * @param  array    $param
     * @return Builder
     */
    public function scopeApplyCriteria(Builder $query, array $param)
    {
        foreach ($param as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $this->applyFilter($query, $field, $value);
            $this->applySearch($query, $field, $value);
        }

        return $query;
    }

    /**
     * @param  Builder  $query
     * @param           $field
     * @param           $value
     */
    protected function applyFilter(Builder $query, $field, $value)
    {
        $method = 'filter'.Str::studly($field);

        $this->callMethod($query, $method, $value);

        if (empty($this->filterable) || !is_array($this->filterable)) {
            return;
        }

        if ($column = $this->getColumn($field, $this->filterable)) {
            $query->where($column, $value);
            return;
        }
    }

    /**
     * @param  Builder  $query
     * @param           $field
     * @param           $value
     */
    protected function applySearch(Builder $query, $field, $value)
    {
        $method = 'search'.Str::studly($field);

        $this->callMethod($query, $method, $value);

        if (empty($this->searchable) || !is_array($this->searchable)) {
            return;
        }

        if ($column = $this->getColumn($field, $this->searchable)) {
            $query->where($column, 'LIKE', "%$value%");
            return;
        }
    }

    /**
     * @param $field
     * @param $property
     * @return string|null
     */
    protected function getColumn($field, $property)
    {
        if (in_array($field, $property)) {
            return $this->table.'.'.$field;
        }

        if (key_exists($field, $property)) {
            return $this->table.'.'.$property[$field];
        }

        return null;
    }

    /**
     * @param  Builder  $query
     * @param           $method
     * @param           $value
     */
    protected function callMethod(Builder $query, $method, $value)
    {
        if (method_exists($this, $method)) {
            $this->{$method}($query, $value);
        }
    }

    /**
     * @param  Builder  $query
     */
    public function scopeOrderDefault(Builder $query)
    {
        $query->orderByDesc($this->getKeyName());
    }
}

<?php

namespace Si6\Base\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Si6\Base\Http\Queryable;

abstract class Criteria
{
    use Queryable;

    protected $table;

    protected $criteria;

    protected $param = [];

    protected $flatten = [];

    public function __construct(array $param = [])
    {
        $this->flatten = collect($this->criteria)->flatten()->toArray();
        $this->param   = $param ?: $this->query($this->flatten);
    }

    public function applyQuery(Builder $query)
    {
        foreach ($this->param as $key => $value) {
            if (is_null($value) || !$this->isValidCriteria($key)) {
                continue;
            }
            $this->applyCriteria($query, $key, $value);
        }
    }

    protected function isValidCriteria($field)
    {
        return in_array($field, $this->flatten);
    }

    protected function applyCriteria(Builder $query, $field, $value)
    {
        $method = 'criteria' . Str::studly($field);
        if (method_exists($this, $method)) {
            $this->{$method}($query, $value);

            return;
        }

        if ($this->isValidCriteriaField($field, 'filter')) {
            $value = is_array($value) ? $value : [$value];
            $query->whereIn("$this->table.$field", $value);

            return;
        }

        if ($this->isValidCriteriaField($field, 'search')) {
            if (is_string($value)) {
                $query->where("$this->table.$field", 'LIKE', "%$value%");
            }

            return;
        }
    }

    protected function isValidCriteriaField($field, $criteriaKey)
    {
        return !empty($this->criteria[$criteriaKey])
            && is_array($this->criteria[$criteriaKey])
            && in_array($field, $this->criteria[$criteriaKey]);
    }
}

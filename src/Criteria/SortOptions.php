<?php

namespace Si6\Base\Criteria;

use Illuminate\Database\Eloquent\Builder;

class SortOptions
{
    protected $sorts;

    protected $default = [
        ['updated_at', 'desc'],
        ['id', 'desc'],
    ];

    public function __construct(array $sorts = [])
    {
        $this->sorts = $sorts ?: $this->default;
    }

    public function applyQuery(Builder $query)
    {
        foreach ($this->sorts as $sort) {
            $query->orderBy($sort[0], $sort[1]);
        }

        return $query;
    }
}

<?php

namespace Si6\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Si6\Base\Http\Queryable;
use Si6\Base\Http\ResponseTrait;

class BaseController extends Controller
{
    use ResponseTrait;
    use Queryable;

    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}

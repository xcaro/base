<?php

namespace Si6\Base\Exceptions;

use Illuminate\Support\Str;

class Forbidden extends BaseException
{
    protected $statusCode = 403;

    public function __construct(string $permission)
    {
        $permission = Str::upper($permission);
        parent::__construct("FORBIDDEN_$permission");
    }
}

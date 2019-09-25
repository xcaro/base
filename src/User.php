<?php

namespace Si6\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'email',
        'status',
        'roles',
        'permissions',
        'last_login_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function permissions()
    {
        return $this->permissions ?? [];
    }
}

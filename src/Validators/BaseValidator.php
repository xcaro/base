<?php

namespace Si6\Base\Validators;

use Illuminate\Http\Request;

class BaseValidator
{
    /** @var Request $request */
    protected $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}

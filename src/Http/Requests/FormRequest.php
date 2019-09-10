<?php

namespace Si6\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

abstract class FormRequest extends HttpFormRequest
{
    use DataTypeRule;

    protected function ids()
    {
        return ['required', 'array'];
    }

    protected function idsElement()
    {
        $rule = ['required'];

        return array_merge($rule, $this->unsignedBigInteger());
    }

    public function messages()
    {
        return require __DIR__ . '/../../../resources/lang/en/validation.php';
    }
}

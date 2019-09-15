<?php

namespace Si6\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

abstract class FormRequest extends HttpFormRequest
{
    use DataTypeRule;

    protected $required = false;

    protected function notRequire()
    {
        $this->required = false;

        return $this;
    }

    protected function require()
    {
        $this->required = true;

        return $this;
    }

    protected function resetRequire()
    {
        $this->notRequire();

        return $this;
    }

    protected function rule()
    {
        $rule = $this->required ? ['required'] : [];
        $this->resetRequire();

        return $rule;
    }

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

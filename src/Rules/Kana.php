<?php

namespace Si6\Base\Rules;

use Illuminate\Contracts\Validation\Rule;

class Kana implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match("/^[ァ-ヶｦ-ﾟー]+$/u", $value);
    }

    public function message()
    {
        return 'The :attribute must be a kana string.';
    }
}

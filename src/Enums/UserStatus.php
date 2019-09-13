<?php

namespace Si6\Base\Enums;

/**
 * @method static static NEWBIE()
 * @method static static OFFICIAL()
 * @method static static IDENTITY_VERIFIED()
 * @method static static LIMITED()
 * @method static static SUSPENDED()
 * @method static static EXPELLED()
 * @method static static LEFT()
 * @method static static DELETED()
 */
class UserStatus extends Enum
{
    const NEWBIE            = 0;
    const OFFICIAL          = 1;
    const IDENTITY_VERIFIED = 2;
    const LIMITED           = 3;
    const SUSPENDED         = 4;
    const EXPELLED          = 5;
    const LEFT              = 6;
    const DELETED           = 7;
}

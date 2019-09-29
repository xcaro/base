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
class UserRole extends Enum
{
    const NEWBIE            = 'user_newbie';
    const OFFICIAL          = 'user_official';
    const IDENTITY_VERIFIED = 'user_identity_verified';
    const LIMITED           = 'user_limited';
    const SUSPENDED         = 'user_suspended';
    const EXPELLED          = 'user_expelled';
    const LEFT              = 'user_left';
    const DELETED           = 'user_deleted';
}

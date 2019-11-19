<?php

namespace Si6\Base\Enums;

/**
 * @method static static SUPER_ADMIN()
 * @method static static OPERATOR_MANAGER()
 * @method static static OPERATOR()
 * @method static static SUPPORTER()
 * @method static static ACCOUNTING_MANAGER()
 * @method static static ACCOUNTING()
 * @method static static STADIUM_MANAGER()
 * @method static static STADIUM_STAFF()
 */
class AdminRole extends Enum
{
    const SUPER_ADMIN        = 'super_admin';
    const OPERATOR_MANAGER   = 'operator_manager';
    const OPERATOR           = 'operator';
    const SUPPORTER          = 'supporter';
    const ACCOUNTING_MANAGER = 'accounting_manager';
    const ACCOUNTING         = 'accounting';
    const STADIUM_MANAGER    = 'stadium_manager';
    const STADIUM_STAFF      = 'stadium_staff';
}

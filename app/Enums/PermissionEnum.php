<?php

namespace App\Enums;

class PermissionEnum extends BaseEnum
{
    const AUTH_LOGOUT = 'auth_get_logout';

    const USER_ME = 'user_get_me';
    const USER_SHOW = 'user_get_show';
    const USER_UPDATE = 'user_patch_update';

    const TRANSACTION_STORE = 'transaction_post_store';
}

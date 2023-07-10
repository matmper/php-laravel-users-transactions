<?php

namespace App\Enums;

class PermissionEnum extends BaseEnum
{
    const AUTH_GET_LOGOUT = 'auth_get_logout';
    const USER_GET_ME = 'user_get_me';
    const TRANSACTION_POST_STORE = 'transaction_post_store';
}

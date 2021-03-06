<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @return bool 
 */
function authCheck(): bool
{
    return Auth::check();
}

/**
 * @return null|\App\Models\User could be null or User instance.
 */
function authUser()
{
    return User::find(Auth::id());
}

/**
 * @return bool
 */
function userIsSupended()
{
    if (!authCheck()) return true;

    $result = User::find(Auth::id());

    return (bool)$result->is_suspended;
}

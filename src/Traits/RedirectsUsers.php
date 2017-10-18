<?php

namespace ArgentCrusade\Support\Traits;

trait RedirectsUsers
{
    public function redirectTo()
    {
        if (is_null($user = auth()->user())) {
            return route('home');
        }

        if ($user->canAccessAdminPanel()) {
            return route('admin.home');
        }

        return route('home');
    }
}

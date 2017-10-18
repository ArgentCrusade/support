<?php

namespace ArgentCrusade\Support\ViewComposers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;

class AuthenticatedUserComposer
{
    /**
     * @var Authenticatable
     */
    protected $user;

    /**
     * AuthenticatedUserComposer constructor.
     *
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('user', $this->user);
    }
}

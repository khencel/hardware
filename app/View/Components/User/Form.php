<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Form extends Component
{
    public $roles;
    public $action;
    public $user;

    public function __construct($roles, $action, $user = null)
    {
        $this->roles = $roles;
        $this->action = $action;
        $this->user = $user;
    }

    public function render()
    {
        return view('components.user.form');
    }
}

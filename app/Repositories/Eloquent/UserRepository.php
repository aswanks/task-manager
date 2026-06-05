<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function allForDropdown()
    {
        return User::orderBy('name')
            ->get(['id', 'name']);
    }
}
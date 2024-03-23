<?php

namespace App\Policies;

use App\Models\Todoitem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodoitemPolicy
{


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Todoitem $todoitem): bool
    {
        return $user->id == $todoitem->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Todoitem $todoitem): bool
    {
        return $user->id == $todoitem->user_id;
    }

}

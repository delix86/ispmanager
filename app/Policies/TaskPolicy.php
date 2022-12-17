<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can delete the given task.
     * Only Admin and Author can delete a task
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function destroy(User $user, Task $task)
    {
        //return $user->id === $task->author_id;
        if($user->id == $task->author_id || $user->isAdmin())
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Determine if the given user can edit the given task.
     * Only Admin and Author can edit a task
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function edit(User $user, Task $task)
    {
        //return $user->id === $task->author_id;
        if($user->id == $task->author_id || $user->isAdmin() || $user->isSupport())
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Determine if the given user can close the given task.
     * Only Admin or Author can close a task
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function close(User $user, Task $task)
    {
        //return $user->id === $task->author_id;
        if($user->id == $task->author_id || $user->isAdmin() || $user->isSupport())
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Determine if the given user can change the given task state.
     * Only Admin, Author  or User can change a task state
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function changestate(User $user, Task $task)
    {
        
        if( $user->isAdmin() || $user->id == $task->author_id || $user->id == $task->user_id || $user->isSupport())
            return TRUE;
        else
            return FALSE;
    }
}

<?php

namespace App\Repositories;

use App\User;
use App\Task;

class TaskRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return LengthAwarePaginator
     */
    public function forUser(User $user)
    {
        if ($user->isAdmin()) {
            //return Task::where('id', '>', '0')->orderBy('created_at', 'dsc')->paginate(25); 
            return Task::orderBy('created_at', 'dsc')->paginate(25);
        } elseif ($user->isSupport()){
            return Task::orderBy('created_at', 'dsc')->paginate(25); 
        }elseif ($user->isWorker()){
            return Task::where( 'user_id' , $user->id )->orWhere('author_id' , $user->id)->orderBy('created_at', 'dsc')->paginate(25); // return all tasks
        }
    }

}

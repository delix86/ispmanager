<?php

namespace App\Repositories;

use App\User;
use App\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @param  null|array  $selectedStates
     * @param  null|array  $selectedLogins
     * @param  null|array  $usersIds
     * @param  null|string  $search
     * @param  null|string  $date
     * @return LengthAwarePaginator
     */
    public function forUser(User $user, $selectedStates = [], $selectedLogins = [], $usersIds = [], $search = null, $date = null)
    {
        $tasksQuery = Task::query();

        if (!empty($selectedStates)) {
            $tasksQuery->whereIn('state_id', $selectedStates);
        }

        if ($search) {
            $tasksQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%'.$search.'%');
                $query->orWhere('text', 'LIKE', '%'.$search.'%');
                $query->orWhere('fio', 'LIKE', '%'.$search.'%');
            });
        }

        if (!empty($usersIds)) {
            $tasksQuery->whereIn('user_id', $usersIds);
        }

        if (!empty($selectedLogins)) {
            $tasksQuery->whereIn('login', $selectedLogins);
        }

        if (!empty($date)) {
            $tasksQuery->where('created_at', 'LIKE', $date .'%');
        }

        if ($user->isAdmin()) {
            //return Task::where('id', '>', '0')->orderBy('created_at', 'dsc')->paginate(25); 
            return $tasksQuery->orderBy('created_at', 'dsc')->paginate(25);
        } elseif ($user->isSupport()){
            return $tasksQuery->orderBy('created_at', 'dsc')->paginate(25);
        } elseif ($user->isWorker()){
            return $tasksQuery->where( 'user_id' , $user->id )->orWhere('author_id' , $user->id)->orderBy('created_at', 'dsc')->paginate(25); // return all tasks
        }
    }
}

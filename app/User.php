<?php

namespace App;

use App\Task;
use App\Right;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'right_id', 'position_id', 'name', 'fio', 'phone', 'address', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all of the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function logs()
    {
        return $this->hasMany('App\Log');
    }
    
    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function right()
    {
        return $this->belongsTo('App\Right');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function isAdmin(){
        //if($this->right_id == Right::where('name', 'admin' )->find(1)->id)
        if($this->right_id == 1)
           return TRUE;
        else
           return FALSE;
    }

    public function isSupport(){
        //if($this->right_id == Right::where('name', 'admin' )->find(1)->id)
        if($this->right_id == 2)
            return TRUE;
        else
            return FALSE;
    }

    public function isWorker(){
        //if($this->right_id == Right::where('name', 'admin' )->find(1)->id)
        if($this->right_id == 3)
           return TRUE;
        else
            return FALSE;
    }

}

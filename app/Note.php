<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'task_id', 'text', 'user_id'
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['task'];  

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}

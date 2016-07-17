<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'task_id', 'text', 'user_id', 'logtype_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function logType()
    {
        return $this->belongsTo('App\Logtype');
    }
}

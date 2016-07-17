<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = ['name'];
    
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logtype extends Model
{
    protected $fillable = ['name'];

    public function logs()
    {
        return $this->hasMany('App\Log');
    }
}

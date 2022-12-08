<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name
 */
class State extends Model
{
    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}

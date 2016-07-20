<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id', 'name', 'type_id', 'priority_id', 'state_id', 'viewed', 'user_id', 'text',
        'uid', 'fio', 'login', 'phone1', 'phone2', 'phone3', 'address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'int', 'author_id' => 'int',
    ];

    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    public function sms()
    {
        return $this->hasMany('App\Sms');
    }

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function priority()
    {
        return $this->belongsTo('App\Priority');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function isViewed()
    {
        return $this->viewed;
    }
}

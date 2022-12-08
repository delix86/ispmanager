<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $name
 * @property int $author_id
 * @property int $type_id
 * @property int $priority_id
 * @property int $state_id
 * @property int $user_id
 * @property bool $viewed
 * @property string $text
 * @property string $uid
 * @property string $fio
 * @property string $login
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $address
 * @property int $parent_id
 * @property State $state
 */
class Task extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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
    public function author()
    {
        return $this->belongsTo(User::class);
    }

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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'note_id', 'text', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function note()
    {
        return $this->belongsTo('App\Note');
    }
}

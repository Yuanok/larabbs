<?php

namespace App\Models;

use App\Models\User;
use App\Models\Topic;

class Reply extends Model
{
    protected $fillable = ['content'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function Topic(){
        return $this->belongsTo(Topic::class);
    }
}

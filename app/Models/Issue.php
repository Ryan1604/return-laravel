<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'qty',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function issueItems() {
        return $this->hasMany('App\Models\IssueItem');
    }
}

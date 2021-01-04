<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueRule extends Model
{
    protected $fillable = ['role_id', 'max_borrow_day', 'max_borrow_item'];

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
}

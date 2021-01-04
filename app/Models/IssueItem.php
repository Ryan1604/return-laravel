<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueItem extends Model
{
    protected $fillable = [
        'issue_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    public function book() {
        return $this->belongsTo('App\Models\Item');
    }

    public function issue() {
        return $this->belongsTo('App\Models\Issue', 'issue_id');
    }
}

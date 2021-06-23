<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'photo',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}

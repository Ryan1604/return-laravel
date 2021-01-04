<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'type',
        'isbn',
        'code',
        'title',
        'year',
        'pages',
        'edition',
        'ebook_available',
        'description',
        'book_cover_url',
        'ebook_url',
        'table_of_contents',
        'total_qty',
        'qty_lost',
        'author_id',
        'category_id',
        'publisher_id',
        'disabled'
    ];

    public function authors() {
        return $this->belongsToMany('App\Models\Author', 'item_authors');
    }

    public function category() {
        return $this->belongsTo('App\Models\Category');
    }

    public function publisher() {
        return $this->belongsTo('App\Models\Publisher');
    }

    public function rack() {
        return $this->belongsTo('App\Models\Rack');
    }

    public function issueItems() {
        return $this->hasMany('App\Models\IssueItem', 'book_id');
    }
}

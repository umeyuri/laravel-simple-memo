<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // public function memos()
    // {
    //     return $this->belongsToMany(Memo::class, 'memo_tags', 'tag_id', 'memo_id');
    // }
}

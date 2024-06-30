<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tags;
use App\Models\MemoTag;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'content','deleted_at'
    ];

    public function tags() {
        // 多対多の先のテーブル（Tag::class)、中間テーブル名、中間テーブル内の外部キー、Tagテーブル外キー
        // belongsToMany(Tag::class);
        return $this->belongsToMany(Tag::class, 'memo_tags', 'memo_id', 'tag_id');
    }

    public function memotags() {
        return $this->hasMany(MemoTag::class);
    }
}

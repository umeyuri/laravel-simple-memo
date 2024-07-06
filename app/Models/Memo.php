<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tags;
use App\Models\MemoTag;
use Illuminate\Support\Facades\Request;

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

    public function getMemoByTagId($query_tag) {
        $memos = Memo::select('memos.*')
            ->leftJoin('memo_tags', 'memos.id', '=', 'memo_tags.memo_id')
            ->when(!empty($query_tag), function($query) use($query_tag) {
                $query->where('memo_tags.tag_id', $query_tag);
            })
            ->where('user_id', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->distinct('memos.id')
            ->get();

        return $memos;

        // // 以下のような書き方でもできる、whenを使わない形：
        // // =====ベースのクエリ=====
        // $query = Memo::query()->select('memos.*')
        //     ->where('user_id', \Auth::id())
        //     ->whereNull('deleted_at')
        //     ->orderBy('updated_at', 'DESC');
        // // パラメータがあった場合はテーブル結合してクエリにあったデータを取得
        // if (!empty($query_tag)) {
        //     $query->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
        //         ->where('memo_tags.tag_id', $query_tag);
        // }
        // $memos = $query->get();
    }
}

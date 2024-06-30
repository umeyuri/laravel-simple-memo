<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
// use Illuminate\Support\Facades\DB;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $tags = Tag::where('user_id', \Auth::id())
        //     ->whereNull('deleted_at')
        //     ->pluck('name', 'id');

        $tags = \Auth::user()->tags()->whereNull('deleted_at')->pluck('name', 'id');

        return view('create', compact('tags'));
    }

    public function store(Request $request) 
    {
        $post = $request->all();
        DB::transaction(function() use($post){
            //メモをDBに新規保存する。
            $memo_id = Memo::insertGetId([
                'content' => $post['content'],
                'user_id' => \Auth::id(),
            ]);

            // 新規タグ名と既存タグ名の重複有無の確認
            // $tag_exists = Tag::where('user_id', \Auth::id())->where('name', $post['new_tag'])->exists();
            $tags_exists= \Auth::user()->tags()->where('name', $post['new_tag'])->exists();

            // 新規タグがあれば保存。memotagテーブルにも紐づけ保存
            if (!empty($post['new_tag']) && !$tag_exists) {
                $tag_id = Tag::insertGetId([
                    'name' => $post['new_tag'],
                    'user_id' => \Auth::id(),
                ]);

                MemoTag::create(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }

            //既存タグがチェックされていた場合memotags Tableにinsertする
            if (!empty($post['tags'])) {
                foreach ($post['tags'] as $tag) {
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }   
            }
        });

        return redirect()->route('home');
    }

    public function edit($id) {
        $edit_memo = Memo::find($id);

        // $tags = Tag::where('user_id', \Auth::id())
        //     ->whereNull('deleted_at')
        //     ->orderBy('updated_at', 'DESC')
        //     ->pluck('name', 'id');
        
        $tags = \Auth::user()->tags()->whereNull('deleted_at')->orderBy('updated_at', 'DESC')->pluck('name', 'id');

        $memo_tags = $edit_memo->tags()->pluck('id')->all();

        return view('edit', compact('edit_memo', 'memo_tags', 'tags'));
    }

    public function update(Request $request) {
        $posts = $request->all();

        DB::transaction(function() use($posts) {

            $memo = Memo::find($posts['memo_id']);

            // メモ内容の更新
            $memo->update([
                'content' => $posts['content'],
            ]);

            // 中間テーブルのデータを一旦全削除し、新規作成
            $memo->tags()->detach();
            if (!empty($posts['tags'])) {
                $memo->tags()->attach($posts['tags']);    
            }

            // 新規タグ名と既存タグ名の重複有無の確認
            //$tag_exists = Tag::where('user_id', \Auth::id())->where('name', $posts['new_tag'])->exists();
            $tag_exists = \Auth::user()->tags()->where('name', $posts['new_tag'])->exists();

            // 新規タグがあれば、タグテーブルに保存。memotagテーブルにも紐づけ保存
            if (!empty($posts['new_tag']) && !$tag_exists) {
                $tag_id = Tag::insertGetId([
                    'name' => $posts['new_tag'],
                    'user_id' => \Auth::id(),
                ]);

                MemoTag::create(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }

        });

        return redirect()->route('home');
    }

    public function destroy(Request $request) {
        $memo = Memo::find($request->memo_id);

        $memo->update([
            'deleted_at' => now(),
        ]);

        // 中間テーブルはdeleted_atカラムを作成していないため物理削除
        $memo->memotags()->delete();

        return redirect()->route('home');
    }
}

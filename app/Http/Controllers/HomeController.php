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
        $memos = Memo::where('user_id', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('create', compact('memos'));
    }

    public function store(Request $request) 
    {
        $post = $request->content;
        
        DB::transaction(function() use($post, $request){
            $memo_id = Memo::insertGetId(['content' => $post, 'user_id' => \Auth::id()]);
            $tag_exists = Tag::where('user_id', \Auth::id())->where('name', $request->new_tag)->exists();
            if (!empty($request->new_tag) && !$tag_exists) {
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $request->new_tag]);
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
        });

        return redirect(route('home'));
    }

    public function edit($id) {
        
        $memos = Memo::where('user_id', \Auth::id())
        ->whereNull('deleted_at')->orderBy('updated_at', 'DESC')
        ->get();

        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }

    public function update(Request $request) {
        $memos = Memo::where('user_id', \Auth::id())
        ->whereNull('deleted_at')->orderBy('updated_at', 'DESC')
        ->get();

        $memo = Memo::find($request->memo_id);
        $memo->update([
            'content' => $request->content,
        ]);

        return redirect()->route('home');
    }

    public function destroy(Request $request) {
        $memos = Memo::where('user_id', \Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at', 'DESC')
        ->get();

        // $memo = Memo::where('id', $request->memo_id)
        //      ->update([
        //     'deleted_at' => now(),
        // ]);
        
        $memo = Memo::find($request->memo_id);
        $memo->update([
            'deleted_at' => now(),
        ]);

        
        return redirect()->route('home');
    }
}

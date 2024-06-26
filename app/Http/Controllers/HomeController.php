<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
            ->whereNull('deleted_at')->orderBy('updated_at', 'DESC')
            ->get();

        return view('create', compact('memos'));
    }

    public function store(Request $request) 
    {
        $post = $request->content;
        Memo::create([
            'content' => $post,
            'user_id' => \Auth::id(),
        ]);

        return redirect(route('home'));
    }

    public function edit($id) {
        
        $memos = Memo::where('user_id', \Auth::id())
        ->whereNull('deleted_at')->orderBy('updated_at', 'DESC')
        ->get();

        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }
}

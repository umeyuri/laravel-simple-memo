<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view) {
            
            $query_tag = Request::query('tag_id');
            
            $memo_model = new Memo;
            $memos = $memo_model->getMemoByTagId($query_tag);

            $tags = Tag::where('user_id', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->pluck('name', 'id');

            $view->with('memos', $memos)
                ->with('tags', $tags);
        });
    }

}

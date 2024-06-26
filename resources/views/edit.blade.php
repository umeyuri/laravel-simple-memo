@extends('layouts.app')

@section('content')
<div class="container p-0">
        <div class="card">
            <div class="card-header">編集メモ作成</div>
            <form class="card-body" action="{{ route('store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="3" placeholder="メモを入力してください">{{ $edit_memo->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
</div>
@endsection

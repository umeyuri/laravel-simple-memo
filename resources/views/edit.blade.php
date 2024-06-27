@extends('layouts.app')

@section('content')
<div class="container p-0">
        <div class="card">
            <div class="card-header">
                メモ編集
                <form method="POST" action="{{ route('destroy') }}">
                    @csrf
                    <input type="hidden" value="{{ $edit_memo->id }}" name="memo_id">
                    <button type="submit">削除</button>
                </form>
            </div>
            <form class="card-body" action="{{ route('update') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $edit_memo->id }}" name="memo_id">
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="3" placeholder="メモを入力してください">{{ $edit_memo->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
</div>
@endsection

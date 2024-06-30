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
                @foreach ($tags as $id => $tag_name)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $id }}" id="inlineCheckbox{{ $id }}"
                            {{ in_array($id, $memo_tags) ? "checked" : ""}}>
                        <label class="form-check-label" for="inlineCheckbox{{ $id }}">
                            {{ $tag_name }}
                        </label>
                    </div>
                @endforeach
                <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新規タグを入力">
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
</div>
@endsection

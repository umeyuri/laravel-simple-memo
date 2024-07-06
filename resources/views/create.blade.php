@extends('layouts.app')

@section('content')
<div class="container p-0">
        <div class="card">
            <div class="card-header">新規メモ作成</div>
            <form class="card-body" action="{{ route('store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="3" placeholder="メモを入力してください"></textarea>
                </div>
                @error('content')
                    <div class="alert alert-danger">メモ内容を入力してください</div>
                @enderror
                @foreach ($tags as $id => $tag_name)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $id }}" id="inlineCheckbox{{ $id }}">
                        <label class="form-check-label" for="inlineCheckbox{{ $id }}">
                            {{ $tag_name }}
                        </label>
                    </div>
                @endforeach
                <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新規タグを入力">
                <button type="submit" class="btn btn-primary">保存</button>
            </form>
        </div>
</div>
@endsection

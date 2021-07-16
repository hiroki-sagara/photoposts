@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="row">
            <aside class="col-sm-4">
                {{-- ユーザ情報 --}}
                @include('users.card')
                
                {{ Form::open(['method'=>'get','route'=>['top.page']]) }}
                {{ Form::select('category_id', $categories, null)}}
                {{ Form::submit('絞り込む',['class'=>'btn btn-outline-danger']) }}
                {{ Form::close() }}
                
            </aside>
            <div class="col-sm-8">
                {{-- 投稿フォーム --}}
                @include('posts.form')
                {{-- 投稿一覧 --}}
                @include('posts.posts')
            </div>
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the PhotoPosts</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection
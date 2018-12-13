@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(\Auth::user()->isAdmin == 1)
                            <a href="{{ route('admin') }}">В админку</a><br>
                        @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Content -->
{{--<div class="container">--}}
    {{--<div class="row">--}}
        {{--<div class="col-lg-8 col-md-10 mx-auto">--}}
            {{--@foreach($articles as $article)--}}
                {{--<div class="post-preview">--}}
                    {{--<a href="{!! route('blog.show', [--}}
                       {{--'id'   => $article->id,--}}
                       {{--'slug' => str_slug($article->title)--}}
                    {{--]) !!}">--}}
                        {{--<h2 class="post-title">--}}
                            {{--{!! $article->title !!}--}}
                        {{--</h2>--}}
                        {{--<h3 class="post-subtitle">--}}
                            {{--{!! $article->short_text !!}--}}
                        {{--</h3>--}}
                    {{--</a>--}}
                    {{--<p class="post-meta">Опубликоал--}}
                        {{--<a href="#">{{$article->author}}</a>--}}
                        {{--в {!! $article->created_at->format('H:i - d/m/Y') !!}</p>--}}
                {{--</div>--}}
            {{--@endforeach--}}



            {{--<hr>--}}
            {{--<!-- Pager -->--}}
            {{--<div class="clearfix">--}}
                {{--<a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
@endsection

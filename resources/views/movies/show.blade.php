@extends('layouts.app')

@section('content')
<h1>{{ $movie->title }}</h1>

@if($movie->poster)
    <img src="{{ asset('storage/' . $movie->poster) }}" width="250">
@endif

<p>{{ $movie->description }}</p>

<p>Posted by:
    <a href="{{ route('users.show', $movie->user) }}">
        {{ $movie->user->name }}
    </a>
</p>

<h3>Genres</h3>
<ul>
@foreach($movie->genres as $genre)
    <li>{{ $genre->name }}</li>
@endforeach
</ul>

<hr>

<h3>Comments</h3>

<div id="comment-list">
    @foreach($movie->comments as $comment)
        @include('comments.single', ['comment' => $comment])
    @endforeach
</div>

<hr>

<h3>Add a Comment</h3>

<form method="POST" action="{{ route('comments.store', $movie) }}">
    @csrf
    <textarea name="content" rows="3" class="form-control"></textarea>
    <button class="btn btn-primary mt-2">Post Comment</button>
</form>

@endsection

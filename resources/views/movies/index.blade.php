@extends('layouts.app')

@section('content')
<h1 class="mb-4">Movie List</h1>

@foreach($movies as $movie)
    <div class="card mb-3 p-3">
        <h3>
            <a href="{{ route('movies.show', $movie) }}">
                {{ $movie->title }}
            </a>
        </h3>

        <p>Posted by:
            <a href="{{ route('users.show', $movie->user) }}">
                {{ $movie->user->name }}
            </a>
        </p>

        @if($movie->poster)
            <img src="{{ asset('storage/' . $movie->poster) }}" width="150">
        @endif
    </div>
@endforeach

<div class="mt-4">
    {{ $movies->links() }}
</div>
@endsection

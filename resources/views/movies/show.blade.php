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
        <div class="border-b py-3">
                <p>{{ $comment->content }}</p>

                <p class="text-sm text-gray-500 mt-1">
                    Posted by
                    <a href="{{ route('users.show', $comment->user) }}" class="text-blue-600 underline">
                        {{ $comment->user->name }}
                    </a>
                    on {{ $comment->created_at->diffForHumans() }}
                </p>
        </div>
    @endforeach
</div>

<hr>

<h3>Add a Comment</h3>

<form id="comment-form" method="POST" action="{{ route('comments.store', $movie) }}">
    @csrf
    <textarea id="comment-content" name="content" rows="3" class="form-control"></textarea>
    <button class="btn btn-primary mt-2">Post Comment</button>
</form>

<script>
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let content = document.getElementById('comment-content').value;
        let url = this.action;
        fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ content })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('comment-list').insertAdjacentHTML('beforeend', data.html);
        document.getElementById('comment-content').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>

@endsection

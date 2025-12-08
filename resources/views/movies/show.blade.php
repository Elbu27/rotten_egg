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
    @foreach ($movie->comments as $comment)
        @include('comments.single', ['comment' => $comment])
    @endforeach
</div>

@auth
    <h4 class="mt-4">Add a Comment</h4>
    <textarea id="comment-content" class="form-control" rows="3"></textarea>
    <button id="submit-comment" class="btn btn-primary mt-2">Post Comment</button>
@else
    <p class="text-muted mt-3">
        You must <a href="{{ route('login') }}">log in</a> to comment.
    </p>
@endauth


<script>
    document.getElementById('submit-comment')?.addEventListener('click', function () {
        let content = document.getElementById('comment-content').value.trim();

        if (content.length === 0) {
            alert("Comment cannot be empty.");
            return;
        }

        fetch("{{ route('movies.comments.store', $movie) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ content })
        })
        .then(response => {
            //when not logged in:
            if (response.status === 401 || response.status === 403 || response.redirected) {
                alert("You must be logged in to post a comment.");
                return null;
            }
            return response.json();
        })
        .then(data => {

            if (!data) return;

            document.getElementById('comment-list')
                .insertAdjacentHTML('beforeend', data.html);

            document.getElementById('comment-content').value = '';
        })
        .catch(error => console.error("AJAX Error:", error));
    });
</script>


@endsection

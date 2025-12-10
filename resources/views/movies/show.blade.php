@extends('layouts.app')

@section('title', $movie->title)

@section('content')
<div class="container py-4">

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            @if($movie->poster)
                <img src="{{ asset('storage/' . $movie->poster) }}"
                     class="img-fluid rounded shadow-sm">
            @else
                <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                     style="height: 320px;">
                    <span class="text-muted">No Image</span>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <h1 class="fw-bold mb-2">{{ $movie->title }}</h1>

            <p class="text-muted mb-1">
                Posted by
                <a href="{{ route('users.show', $movie->user) }}">
                    {{ $movie->user->name }}
                </a>
            </p>

            <div class="mb-2">
                @foreach($movie->genres as $genre)
                    <span class="badge bg-secondary me-1">{{ $genre->name }}</span>
                @endforeach
            </div>

            <p class="mt-3">
                {{ $movie->description }}
            </p>

            @if($movie->trailer_url)
                <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-primary mt-2">
                    Watch Trailer
                </a>
            @endif
        </div>
    </div>
    <hr>
    @auth
        @can('update', $movie)
            <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning me-2">
                Edit Movie
            </a>
        @endcan

        @can('delete', $movie)
            <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this movie?')">
                    Delete Movie
                </button>
            </form>
        @endcan
    @endauth

    <hr>

    <div class="row">
        <div class="col-md-8">
            <h4 class="mb-3">Comments</h4>

            <div id="comment-list" class="mb-4">
                @foreach ($movie->comments as $comment)
                    @include('comments.single', ['comment' => $comment])
                @endforeach
            </div>

            @auth
                <h5 class="mb-2">Add a Comment</h5>
                <textarea id="comment-content" class="form-control mb-2" rows="3"></textarea>
                <button id="submit-comment" class="btn btn-primary">Post Comment</button>
            @else
                <p class="text-muted">
                    You must <a href="{{ route('login') }}">log in</a> to comment.
                </p>
            @endauth
        </div>
    </div>
</div>

@auth
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
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest" 

            },
            body: new URLSearchParams({
                content: content
            })
        })
        .then(response => {
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
@endauth
@endsection

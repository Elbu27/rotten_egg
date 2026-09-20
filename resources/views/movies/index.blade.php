@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4 fw-bold">Movies</h1>

    <!-- SEARCH + FILTER -->
    <form method="GET" action="{{ route('movies.index') }}" class="mb-4">
        <div class="row g-3">

            <!-- Search -->
            <div class="col-md-4">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search movies…" 
                    class="form-control"
                >
            </div>

            <!-- Genre -->
            <div class="col-md-3">
                <select name="genre" class="form-select">
                    <option value="">All Genres</option>

                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" @selected(request('genre') == $genre->id)>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter -->
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

            <!-- Clear -->
            @if(request('q') || request('genre'))
                <div class="col-md-2">
                    <a href="{{ route('movies.index') }}" class="btn btn-secondary w-100">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </form>

    <!-- RESULTS COUNT -->
    <p class="text-muted">
        Showing {{ $movies->count() }} of {{ $movies->total() }} results
    </p>

    <!-- MOVIE GRID -->
    <div class="row">
        @foreach($movies as $movie)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">

                <!-- Poster -->
                @if($movie->poster)
                    <img src="{{ asset('storage/' . $movie->poster) }}" 
                         class="card-img-top"
                         style="height: 350px; object-fit: cover;">
                @else
                    <div class="d-flex justify-content-center align-items-center bg-light" 
                         style="height:350px;">
                        <span class="text-muted">No Image</span>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $movie->title }}</h5>

                    <p class="card-text text-muted">
                        {{ Str::limit($movie->description, 100) }}
                    </p>

                    <p class="card-text">
                        <small class="text-muted">
                            Posted by
                            <a href="{{ route('users.show', $movie->user) }}" class="fw-semibold">
                                {{ $movie->user->name }}
                            </a>
                        </small>
                    </p>

                    <!-- Genres -->
                    <div class="mb-3">
                        @foreach($movie->genres as $genre)
                            <span class="badge bg-secondary me-1">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    <!-- Button at bottom -->
                    <a href="{{ route('movies.show', $movie) }}" 
                       class="btn btn-primary mt-auto">
                        View Movie
                    </a>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $movies->links() }}
    </div>

    <!-- CREATE BUTTON: use the same policy as the route middleware. -->
    @can('create', \App\Models\Movie::class)
        <div class="mt-3">
            <a href="{{ route('movies.create') }}" class="btn btn-success">
                + Create Movie
            </a>
        </div>
    @endcan

</div>
@endsection

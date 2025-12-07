@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Movies</h1>

    <!--SEARCH + FILTER FORM-->
    <form method="GET" action="{{ route('movies.index') }}" class="mb-4">
        <div class="row g-3">

            <!-- Search box -->
            <div class="col-md-5">
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control"
                    placeholder="Search movies..."
                >
            </div>

            <!-- Genre dropdown -->
            <div class="col-md-4">
                <select name="genre" class="form-select">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" 
                            @selected(request('genre') == $genre->id)>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter button -->
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

            <!-- Clear button -->
            @if(request('q') || request('genre'))
                <div class="col-md-1">
                    <a href="{{ route('movies.index') }}" class="btn btn-secondary w-100">
                        Clear
                    </a>
                </div>
            @endif

        </div>
    </form>

    <!-- RESULTS COUNT -->
    @if($movies->total() > 0)
        <p class="text-muted">
            Showing {{ $movies->count() }} of {{ $movies->total() }} results
        </p>
    @endif

    <!-- MOVIE LIST -->
    @foreach ($movies as $movie)
        <div class="card mb-3">
            <div class="row g-0">

                <!-- Poster -->
                @if ($movie->poster)
                    <div class="col-md-4">
                        <img 
                            src="{{ asset('storage/' . $movie->poster) }}"
                            class="img-fluid rounded-start"
                            alt="{{ $movie->title }}">
                    </div>
                @endif

                <div class="col-md-8">
                    <div class="card-body">

                        <!-- Title -->
                        <h5 class="card-title">
                            <a href="{{ route('movies.show', $movie) }}" class="text-decoration-none">
                                {{ $movie->title }}
                            </a>
                        </h5>

                        <!-- Description -->
                        <p class="card-text">
                            {{ Str::limit($movie->description, 120) }}
                        </p>

                        <!-- Posted by -->
                        <p class="text-muted mb-2">
                            Posted by 
                            <a href="{{ route('users.show', $movie->user) }}">
                                {{ $movie->user->name }}
                            </a>
                        </p>

                        <!-- Genres -->
                        @if ($movie->genres->count() > 0)
                            <p class="mb-2">
                                @foreach ($movie->genres as $genre)
                                    <span class="badge bg-secondary">{{ $genre->name }}</span>
                                @endforeach
                            </p>
                        @endif

                        <!-- View button -->
                        <a href="{{ route('movies.show', $movie) }}" 
                           class="btn btn-primary btn-sm">
                           View
                        </a>

                    </div>
                </div>

            </div>
        </div>
    @endforeach

    @auth
        @if(auth()->user()->isProducer())
            <a href="{{ route('movies.create') }}" class="btn btn-primary">
                Create Movie
            </a>
        @endif
    @endauth

    <!-- Paginate -->
    <div class="mt-3">
        {{ $movies->links() }}
    </div>
</div>
@endsection

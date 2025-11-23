@extends('layouts.app')

@section('content')
<h1>Edit Movie</h1>

<form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Title</label>
    <input type="text" name="title" value="{{ $movie->title }}" class="form-control mb-2">

    <label>Description</label>
    <textarea name="description" class="form-control mb-2">{{ $movie->description }}</textarea>

    <label>Release Year</label>
    <input type="number" name="release_year" value="{{ $movie->release_year }}" class="form-control mb-2">

    <label>Age Rating</label>
    <input type="number" name="age_rating" value="{{ $movie->age_rating }}" class="form-control mb-2">

    <label>Poster</label>
    <input type="file" name="poster" class="form-control mb-2">

    @if($movie->poster)
        <img src="{{ asset('storage/' . $movie->poster) }}" width="150" class="mb-3">
    @endif

    <label>Genres</label>
    <select name="genres[]" multiple class="form-control mb-3">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}"
                {{ $movie->genres->contains($genre) ? 'selected' : '' }}>
                {{ $genre->name }}
            </option>
        @endforeach
    </select>

    <button class="btn btn-primary">Update</button>
</form>

@endsection

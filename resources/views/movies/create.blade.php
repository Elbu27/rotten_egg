@extends('layouts.app')

@section('content')
<h1>Create Movie</h1>

<form method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
    @csrf

    <label>Title</label>
    <input type="text" name="title" class="form-control mb-2">

    <label>Description</label>
    <textarea name="description" class="form-control mb-2"></textarea>

    <label>Release Year</label>
    <input type="number" name="release_year" class="form-control mb-2">

    <label>Age Rating</label>
    <input type="number" name="age_rating" class="form-control mb-2">

    <label>Poster</label>
    <input type="file" name="poster" class="form-control mb-2">

    <label>Genres</label>
    <select name="genres[]" multiple class="form-control mb-3">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
        @endforeach
    </select>

    <button class="btn btn-primary">Submit</button>
</form>

@endsection

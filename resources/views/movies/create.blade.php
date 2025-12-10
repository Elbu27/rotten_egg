@extends('layouts.app')

@section('title', 'Create Movie')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Create Movie</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-control @error('title') is-invalid @enderror">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Release Year</label>
                        <input type="number" name="release_year" value="{{ old('release_year') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Age Rating</label>
                        <input type="number" name="age_rating" value="{{ old('age_rating') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Poster Image</label>
                    <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror">
                    @error('poster')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Genres</label>
                    <select name="genres[]" class="form-select" multiple>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}"
                                @if(collect(old('genres'))->contains($genre->id)) selected @endif>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl (Cmd on Mac) to select multiple.</div>
                </div>

                <button class="btn btn-success">Create Movie</button>
            </form>
        </div>
    </div>
</div>
@endsection

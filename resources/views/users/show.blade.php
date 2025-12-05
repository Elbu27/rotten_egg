@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">{{ $user->name }}</h1>

    {{-- Avatar will be added in Phase 9 --}}
    @if($user->avatar_path ?? false)
        <img src="{{ asset('storage/'.$user->avatar_path) }}" 
             alt="{{ $user->name }} avatar" 
             class="w-24 h-24 rounded-full mb-4 object-cover">
    @endif

    <h2 class="text-xl font-semibold mt-6 mb-2">Movies</h2>
    <ul class="space-y-2">
        @forelse($user->movies as $movie)
            <li>
                <a href="{{ route('movies.show', $movie) }}" class="text-blue-600 underline">
                    {{ $movie->title }}
                </a>
            </li>
        @empty
            <li class="text-gray-500">No movies yet.</li>
        @endforelse
    </ul>

    <h2 class="text-xl font-semibold mt-6 mb-2">Comments</h2>
    <ul class="space-y-2">
        @forelse($user->comments as $comment)
            <li class="text-sm">
                On 
                <a href="{{ route('movies.show', $comment->movie) }}" class="text-blue-600 underline">
                    {{ $comment->movie->title }}
                </a>:
                “{{ $comment->body }}”
            </li>
        @empty
            <li class="text-gray-500">No comments yet.</li>
        @endforelse
    </ul>
</div>
@endsection

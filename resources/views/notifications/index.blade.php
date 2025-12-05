@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    @forelse($notifications as $notification)
        @php $data = $notification->data; @endphp

        <div class="border rounded p-3 mb-3 bg-gray-50">
            <p>
                <strong>{{ $data['from_user_name'] }}</strong>
                commented on your movie:
            </p>

            <p class="mt-1 italic text-gray-700">"{{ $data['comment_body'] }}"</p>

            <a href="{{ route('movies.show', $data['movie_id']) }}" class="text-blue-600 underline">
                View movie
            </a>

            <p class="text-xs text-gray-500 mt-1">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    @empty
        <p>No notifications yet.</p>
    @endforelse
</div>
@endsection

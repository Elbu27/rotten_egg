@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Notifications</h1>

    @if($notifications->count() === 0)
        <p class="text-muted">No notifications yet.</p>
    @else
        <div class="list-group">
            @foreach($notifications as $notification)
                @php $data = $notification->data; @endphp
                <a href="{{ route('movies.show', $data['movie_id'] ?? null) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between
                          {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                    <div>
                        <strong>{{ $data['from_user_name'] ?? 'Someone' }}</strong>
                        commented on your movie:
                        “{{ Str::limit($data['comment_body'] ?? '', 60) }}”
                    </div>
                    <small class="text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </a>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

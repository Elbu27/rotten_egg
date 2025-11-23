<div class="border p-2 mb-2">
    <strong>
        <a href="{{ route('users.show', $comment->user) }}">
            {{ $comment->user->name }}
        </a>
    </strong>
    <p>{{ $comment->content }}</p>
    <small>{{ $comment->created_at->diffForHumans() }}</small>
</div>

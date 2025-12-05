<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;

class NewCommentOnMovie extends Notification
{
    use Queueable;
    public Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->commment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'movie_id'       => $this->comment->movie_id,
            'comment_id'     => $this->comment->id,
            'comment_body'   => $this->comment->content,
            'from_user_id'   => $this->comment->user_id,
            'from_user_name' => $this->comment->user->name,
        ];
    }
}

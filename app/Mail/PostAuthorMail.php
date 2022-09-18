<?php

namespace App\Mail;

use App\Comments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostAuthorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comments $comments)
    {
        $this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.comments.post_author_mail')
                    ->subject('Full Stack Web Dev PKS DS');
    }
}

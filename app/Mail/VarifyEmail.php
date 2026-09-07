<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VarifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     return $this->subject('Mail for user')
    //         ->view('emails.varify_email');
    // }
    
    public function build()
    {
        return $this
            ->from('rtuid.in@gmail.com', 'RTUID Team')
            ->replyTo('rtuid.in@gmail.com', 'RTUID Support')
            ->subject('Verify Your Email - RTUID')
            ->view('emails.varify_email');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancelMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $booking_data;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking_data)
    {
        $this->booking_data = $booking_data;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mail from cityroom.in')
                    ->view('emails.cancle');
    }
}

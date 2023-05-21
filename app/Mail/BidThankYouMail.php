<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BidThankYouMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $info;

    public function __construct($info)
    {
        \Illuminate\Support\Facades\Log::info($info);
        $this->info = $info;
    }

    public function build()
    {
        return $this->subject($this->info['subject'])->view('users.emails.bid_thank_you_mail');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $url, $role)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->url = $url;
        $this->role = $role;
    }

    public function content()
    {
        return new Content(
            view: 'website.pages.emails.alert',
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->with(['data' => $this->data, 'subject' => $this->subject, 'url' => $this->url, 'role' => $this->role])
                    ->markdown('website.pages.emails.alert'); // Anda perlu membuat view untuk email ini, gantilah 'emails.email_sample' dengan nama view yang sesuai.
    }
}

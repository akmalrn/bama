<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public $siteName;

    public $logoUrl;

    public $logoPath;

    public function __construct($data, $siteName, $logoUrl = null, $logoPath = null)
    {
        $this->data = $data;
        $this->siteName = $siteName;
        $this->logoUrl = $logoUrl;
        $this->logoPath = $logoPath;
    }

    public function build()
    {
        $senderName = $this->data['name'] ?? 'Formulir Website';

        return $this->subject("Pesan Baru dari {$this->siteName} - {$senderName}")
            ->view('frontend.emails.contact');
    }
}

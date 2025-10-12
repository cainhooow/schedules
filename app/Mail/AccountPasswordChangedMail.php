<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountPasswordChangedMail extends Mailable
{
     use Queueable, SerializesModels;

     /**
      * Create a new message instance.
      */
     public function __construct(public User $user)
     {
          //
     }

     /**
      * Get the message envelope.
      */
     public function envelope(): Envelope
     {
          return new Envelope(
               subject: 'Sua senha foi alterada com sucesso',
          );
     }

     /**
      * Get the message content definition.
      */
     public function content(): Content
     {
          return new Content(
               view: 'mail.account.password-changed',
          );
     }

     /**
      * Get the attachments for the message.
      *
      * @return array<int, \Illuminate\Mail\Mailables\Attachment>
      */
     public function attachments(): array
     {
          return [];
     }
}

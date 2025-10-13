<?php

namespace App\Notifications;

use App\Models\Commitment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommitment extends Notification
{
     use Queueable;
     /**
      * Summary of __construct
      * @param string $messageTo
      * @param \App\Models\User $contractor
      * @param \App\Models\User $serviceProvider
      * @param \App\Models\Commitment $commitment
      */
     public function __construct(
          public string $messageTo = "client",
          protected User $contractor,
          protected User $serviceProvider,
          protected Commitment $commitment
     ) {
     }

     /**
      * Get the notification's delivery channels.
      *
      * @return array<int, string>
      */
     public function via(object $notifiable): array
     {
          return ['mail'];
     }

     /**
      * Get the mail representation of the notification.
      */
     public function toMail(object $notifiable): MailMessage
     {
          $view = match ($this->messageTo) {
               'client' => 'mail.commitment.client.new',
               'provider' => 'mail.commitment.provider.new',
          };

          return (new MailMessage)
               ->view($view, [
                    'contractor' => $this->contractor,
                    'serviceProvider' => $this->serviceProvider,
                    'commitment' => $this->commitment
               ]);
     }

     public function databaseType(object $notifiable): string
     {
          return 'new-commiment';
     }

     public function initialDatabaseReadAtValue(): ?Carbon
     {
          return null;
     }

     /**
      * Get the array representation of the notification.
      *
      * @return array<string, mixed>
      */
     public function toArray(object $notifiable): array
     {
          return [
               'commitment' => $this->commitment,
               'schedule' => $this->commitment->schedule,
               'provider' => $this->serviceProvider->profile,
               'contractor' => $this->contractor->profile,
          ];
     }
}

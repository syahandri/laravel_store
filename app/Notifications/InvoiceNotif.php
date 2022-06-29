<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceNotif extends Notification implements ShouldQueue
{
   use Queueable;

   /**
    * Create a new notification instance.
    *
    * @return void
    */
   protected $name, $invoice, $subject, $message, $status;
   public function __construct($name, $invoice, $subject, $message, $status)
   {
      $this->name = $name;
      $this->invoice = $invoice;
      $this->subject = $subject;
      $this->message = $message;
      $this->status = $status;
   }

   /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
   public function via($notifiable)
   {
      return ['mail'];
   }

   /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
   public function toMail($notifiable)
   {
      return (new MailMessage)
         ->subject($this->subject)
         ->greeting("Hai, $this->name")
         ->line("Pesanan kamu dengan invoice $this->invoice, $this->message.")
         ->action("Lihat Pesanan", url("/orders/status/$this->status"))
         ->line("Terimakasih sudah menggunakan laravel store!");
   }

   /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
   public function toArray($notifiable)
   {
      return [
         //
      ];
   }
}

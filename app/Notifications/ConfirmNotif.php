<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmNotif extends Notification
{
   use Queueable;

   /**
    * Create a new notification instance.
    *
    * @return void
    */
   protected $user, $invoice, $name, $bank, $attach;
   public function __construct($user, $invoice, $name, $bank, $attach)
   {
      $this->user = $user;
      $this->invoice = $invoice;
      $this->name = $name;
      $this->bank = $bank;
      $this->attach = $attach;
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
         ->subject('Konfirmasi Pesanan')
         ->greeting('Hello,')
         ->line("$this->user telah melakukan konfirmasi pesanan untuk invoice $this->invoice atas nama $this->name melalui bank $this->bank")
         ->action('Lihat Pesanan', url('/store-admin/order/confirm'))
         ->attach(public_path("storage/img/$this->attach"));
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

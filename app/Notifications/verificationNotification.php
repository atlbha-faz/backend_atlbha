<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verificationNotification extends Notification
{
    use Queueable;
    private $data;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
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
        ->subject($this->data['type'])
        ->line($this->data['message'])
        ->line('شكرا');
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
             'user_id'=>$this->data['user_id'],
             'message' => $this->data['message'],
             'store_id' => $this->data['store_id'],
             'type'=> $this->data['type'],
             'object_id'=> $this->data['object_id']
         ];
     }
    public function toDatabase($notifiable)
    {
        return [

            'user_id'=>$this->data['user_id'],
            'message' => $this->data['message'],
            'store_id' => $this->data['store_id'],
            'type'=> $this->data['type'],
            'object_id'=> $this->data['object_id']

        ];
    }
}

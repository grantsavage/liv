<?php

namespace App\Notifications;

use Auth;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FriendRequestReceived extends Notification
{
    use Queueable;

    public $sender;
    //public $receiver;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $sender)
    {
        $this->sender = $sender;
        //$this->receiver = $receiver;
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
                    ->subject("You have a new friend request!")
                    ->line($this->sender->name.' sent you a friend request!')
                    ->action('Accept Their Request', 'https://laravel.com/user/'.$this->sender->username)
                    ->line('Thanks for using Liv');
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
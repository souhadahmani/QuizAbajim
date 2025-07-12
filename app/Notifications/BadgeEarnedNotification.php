<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BadgeEarnedNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param array $data
     */
    public function __construct($title, $message, $data)
    {
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // You can add 'mail' or other channels as needed
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->line('الوسام: ' . $this->data['badge_name'])
            ->line('الوصف: ' . $this->data['description'])
            ->action('عرض اللوحة', url('/student/dashboard'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'badge_name' => $this->data['badge_name'],
            'description' => $this->data['description'],
            'created_at' => now(),
        ];
    }
}
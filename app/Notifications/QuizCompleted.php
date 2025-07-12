<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class QuizCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quizTitle;
    protected $score;
    protected $percentage;

    public function __construct($quizTitle, $score, $percentage)
    {
        $this->quizTitle = $quizTitle;
        $this->score = $score;
        $this->percentage = $percentage;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تم إكمال الإختبار: ' . $this->quizTitle,
            'message' => 'لقد أكملت الإختبار بنجاح! النتيجة: ' . round($this->percentage) . '%',
            'sender' => 'system',
            'type' => 'single',
            'data' => [
                'score' => $this->score,
                'percentage' => $this->percentage,
                'quiz_title' => $this->quizTitle,
                'read' => false, // Add read flag
            ],
            'created_at' => now()->timestamp,
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $notifiable->id,
            'sender_id' => null,
            'group_id' => null,
            'title' => 'تم إكمال الإختبار: ' . $this->quizTitle,
            'message' => 'لقد أكملت الإختبار بنجاح! النتيجة: ' . round($this->percentage) . '%',
            'sender' => 'system',
            'type' => 'single',
            'data' => [
                'score' => $this->score,
                'percentage' => $this->percentage,
                'quiz_title' => $this->quizTitle,
                'read' => false,
            ],
            'created_at' => now()->timestamp,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'title' => 'تم إكمال الإختبار: ' . $this->quizTitle,
            'message' => 'لقد أكملت الإختبار بنجاح! النتيجة: ' . round($this->percentage) . '%',
            'score' => $this->score,
            'percentage' => $this->percentage,
            'quiz_title' => $this->quizTitle,
            'created_at' => now()->timestamp,
        ]);
    }

    public function broadcastOn()
    {
        return ['notifications.' . $this->notifiable->id];
    }

    public function broadcastAs()
    {
        return 'notification.sent';
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }
    public function via(object $notifiable): array
    {
        return ['database']; 
    }


    public function toArray(object $notifiable): array
    {
        return [
            'task_id'   => $this->task->id,
            'title'     => $this->task->title,
            'assigned_by' => auth()->user()->name,
            'message' => 'You have been assigned a new tour plan task by Manager ' . auth()->user()->name . ': ' . $this->task->title,
            'url'       => url('mr/assigned-tour-plans'),
            'icon' => 'fas fa-tasks',
        ];
    }
}

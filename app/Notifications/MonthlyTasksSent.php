<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MonthlyTasksSent extends Notification
{
    use Queueable;

    protected $taskMonth;

    public function __construct($taskMonth)
    {
        $this->taskMonth = $taskMonth;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'month'       => $this->taskMonth,
            'assigned_by' => auth()->user()->name,
            'message'     => auth()->user()->name . " has submitted monthly tasks for {$this->taskMonth}. Please review and approve them.",
            'url'         => url('/manager/tasks-calendar-for-approval'), 
            'icon'        => 'fas fa-tasks',
        ];
    }

}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftAssignedNotification extends Notification
{
    use Queueable;

    protected $shift;

    /**
     * Create a new notification instance.
     */
    public function __construct($shift)
    {
        $this->shift = $shift;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'shift',
            'title' => 'Ca làm việc mới',
            'message' => 'Bạn đã được phân công ca làm mới vào ngày ' . \Carbon\Carbon::parse($this->shift->StartTime)->format('d/m/Y'),
            'time' => \Carbon\Carbon::parse($this->shift->StartTime)->format('H:i') . ' - ' . \Carbon\Carbon::parse($this->shift->EndTime)->format('H:i'),
            'link' => route('employee.shifts'),
            'icon' => 'schedule'
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentBookedNotification extends Notification
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
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
        $serviceName = $this->appointment->services->first()->ServiceName ?? 'dịch vụ';
        
        return [
            'type' => 'appointment',
            'title' => 'Lịch hẹn mới',
            'message' => 'Khách hàng ' . ($this->appointment->customer->FullName ?? 'Ẩn danh') . ' vừa đặt lịch ' . $serviceName,
            'time' => \Carbon\Carbon::parse($this->appointment->AppointmentTime)->format('d/m/Y H:i'),
            'link' => route('admin.appointments.show', $this->appointment->AppointmentID),
            'icon' => 'calendar_month'
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeAccountActivated extends Notification
{
    use Queueable;

    public $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Account is Activated')
            ->greeting('Hello ' . $this->employee->first_name . ',')
            ->line('Your account has been successfully activated.')
            ->line('You can now log in using your email address: ' . $this->employee->email_address)
            ->line('Your password is: ' . $this->employee->company_id) // Include the password
            ->action('Login', url('/login')) // Adjust the URL as needed
            ->line('or Login to your HRIS APP')
            ->line('Thank you for being part of our organization!');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}

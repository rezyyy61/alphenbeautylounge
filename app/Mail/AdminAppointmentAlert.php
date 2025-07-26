<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAppointmentAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $conflicts;

    public function __construct(Appointment $appointment, $conflicts = [])
    {
        $this->appointment = $appointment;
        $this->conflicts = $conflicts;
    }

    public function build()
    {
        return $this->subject('📅 Nieuwe afspraak ingepland')
            ->markdown('emails.admin_appointment');
    }
}

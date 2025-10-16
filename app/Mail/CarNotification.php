<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class CarNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $cars;
    public int $day;

    public function __construct($cars, $day)
    {
        $this->cars = $cars;
        $this->day = $day;
    }

    public function build(): CarNotification
    {
        return $this->view('emails.car_notification')
            ->with(['cars' => $this->cars, 'day' => $this->day]);
    }
}

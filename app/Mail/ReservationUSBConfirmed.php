<?php

namespace App\Mail;

use App\usbreservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationUSBConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $amount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $order_id)
    {
        $this->name = $name;
        $this->amount = usbreservation::where('id', $order_id)->first()->amount;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('USB Showcase Infinity movie order paid') ->markdown('mailUsb.confirmation');
    }
}

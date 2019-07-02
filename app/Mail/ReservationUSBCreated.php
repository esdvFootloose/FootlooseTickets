<?php

namespace App\Mail;

use App\usbreservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationUSBCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $amount, $payment_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $order_id, $payment_url)
    {
        $this->name = $name;
        $this->payment_url = $payment_url;
        $this->amount = usbreservation::where('id', $order_id)->first()->amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('USB Showcase infinity reservation created')->markdown('mailUsb.reservation');
    }
}

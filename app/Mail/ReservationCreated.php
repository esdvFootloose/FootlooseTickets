<?php

namespace App\Mail;

use App\Reservation;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $tickets, $payment_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $order_id, $payment_url)
    {
        $this->name = $name;
        $this->payment_url = $payment_url;
        $ordered_tickets = Reservation::all()->where('order_id', $order_id);
        $this->tickets = '';

        foreach ($ordered_tickets as $ticket) {
            $type = Ticket::all()->where('id', $ticket->ticket_id)->last()->type;
            $this->tickets = $this->tickets.'- '.$ticket->amount.'x '.$type."
";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.reservation');
    }
}

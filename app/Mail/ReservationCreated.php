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

    public $name, $tickets;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $order_id)
    {
        $this->name = $name;
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
        return $this->markdown('reservation');
    }
}

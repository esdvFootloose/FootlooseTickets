<?php

namespace App\Mail;

use App\Reservation;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $tickets, $paid_time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $order_id, $paid_time)
    {
        $this->name = $name;
        $ordered_tickets = Reservation::where('order_id', $order_id)->get();
        $this->tickets = '';

        foreach ($ordered_tickets as $ticket) {
            $type = Ticket::all()->where('id', $ticket->ticket_id)->last()->type;
            $this->tickets = $this->tickets . '- ' . $ticket->amount . 'x ' . $type . "
";
        }        $this->paid_time = $paid_time;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.confirmation');
    }
}

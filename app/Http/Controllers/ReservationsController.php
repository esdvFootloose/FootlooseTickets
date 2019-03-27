<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCreated;
use App\Reservation;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $tickets = Ticket::all();

        $validated = request()->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email'],
        ]);

        $last_reservation = Reservation::all()->last();
        $order_id = 0;
        if (count($last_reservation) > 0) {
            $order_id = $last_reservation->order_id + 1;
        }

        foreach ($tickets as $ticket) {
            $name = 'ticket-'.$ticket->id;
            $amount = 'ticket-'.$ticket->id.'-number';
            if(request($name) == 'on'){
                Reservation::create([
                    'name' => request('name'),
                    'email' => request('email'),
                    'ticket_id' => $ticket->id,
                    'amount' => (int)request($amount),
                    'order_id' => $order_id
                ]);
            }
        }

        Mail::to(request('email'))->send(
            new ReservationCreated(request('name'), $order_id)
        );

        return view('success');
    }
}
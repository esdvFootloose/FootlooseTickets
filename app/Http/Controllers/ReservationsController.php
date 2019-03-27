<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Ticket;
use Illuminate\Http\Request;

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


        foreach ($tickets as $ticket) {
            $name = 'ticket-'.$ticket->id;
            $amount = 'ticket-'.$ticket->id.'-number';
            if(request($name) == 'on'){
                Reservation::create([
                    'name' => request('name'),
                    'email' => request('email'),
                    'ticket_id' => $ticket->id,
                    'amount' => (int)request($amount)
                ]);
            }
        }

        return view('success');
    }
}
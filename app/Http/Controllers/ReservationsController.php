<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCreated;
use App\Reservation;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'destroy', 'edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = DB::table('reservations')
            ->join('tickets', 'ticket_id', '=', 'tickets.id')
            ->select('reservations.*', 'reservations.name', 'reservations.email', 'tickets.type', 'reservations.amount')
            ->get();

        return view('reservation.index', compact('reservations'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tickets = Ticket::all();
        return view('reservation.create', compact('tickets'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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

        $order_created = false;
        foreach ($tickets as $ticket) {
            $name = 'ticket-' . $ticket->id;
            $amount = 'ticket-' . $ticket->id . '-number';
            if (request($name) == 'on') {
                Reservation::create($validated +
                    [
                        'ticket_id' => $ticket->id,
                        'amount' => (int)request($amount),
                        'order_id' => $order_id
                    ]);
                $order_created = true;
            }
        }

        if (!$order_created) {
            return redirect('/')->withErrors('Please select at least one ticket')->withInput();
        }

        Mail::to(request('email'))->send(
            new ReservationCreated(request('name'), $order_id)
        );

        return view('success');
    }
}
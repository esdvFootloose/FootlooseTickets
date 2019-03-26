<?php

namespace App\Http\Controllers;

use App\Reservation;
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
        Reservation::create([
            'name' => request('name'),
            'email' => request('email'),
            'ticket_id' => 1
        ]);

       return view('success');
    }
}
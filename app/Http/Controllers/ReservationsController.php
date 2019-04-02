<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCreated;
use App\Reservation;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ReservationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'destroy', 'edit', 'download']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = $this->mergeTicketsReservations();

        $paid = json_decode(Artisan::call('tikkie:get', []))->paymentRequests;
        $paid = collect($paid);

        foreach ($reservations as $reservation) {
            if ($reservation->paid == 0) {
                $external_id = 'ticket-'.$reservation->order_id;
                $tikkie = $paid->where('externalId', '=', $external_id)->first();
                $payment = collect($tikkie->payments)->where('onlinePaymentStatus', '=', 'PAID')->first();
                $reservation->paid = empty($payment) ? false : true;
            }
        }

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
     * @param \Illuminate\Http\Request $request
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
        $total = 0;
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
                $total += (int)request($amount) * $ticket->price * 100;
                $order_created = true;
            } else if ((int)request($amount) > 0) {
                return redirect('/')->withErrors('Please select the tickets if an amount of tickets is entered')->withInput();
            }
        }

        $description = 'Showcase Infinity tickets';
        if (!$order_created) {
            return redirect('/')->withErrors('Please select at least one ticket')->withInput();
        }

        $tikkie = json_decode(Artisan::call('tikkie:create', ['amount' => $total, 'description' => $description, 'order_id' => $order_id]));

        Mail::to(request('email'))->send(
            new ReservationCreated(request('name'), $order_id, $tikkie->paymentRequestUrl)
        );

        return view('success');
    }

    public function download()
    {
        $reservations = $this->mergeTicketsReservations();
        $csv = array('ID,Name,Email,Ticket,Amount,Paid');

        foreach ($reservations as $entry) {
            $csv[] = $entry->id . ',' . $entry->name . ',' . $entry->email . ',' . $entry->type . ',' . $entry->amount . ',' . $entry->paid;
        }

        $filename = 'reservations-' . date('d-m-Y') . ".csv";
        $file_path = base_path() . '/' . $filename;
        $file = fopen($file_path, "w+");
        foreach ($csv as $data) {
            fputcsv($file, explode(',', $data));
        }
        fclose($file);

        $headers = ['Content-Type' => 'application/csv'];
        return response()->download($file_path, $filename, $headers);
    }

    private function mergeTicketsReservations()
    {
        return DB::table('reservations')
            ->join('tickets', 'ticket_id', '=', 'tickets.id')
            ->select('reservations.*', 'reservations.name', 'reservations.email', 'tickets.type', 'reservations.amount')
            ->get();
    }
}
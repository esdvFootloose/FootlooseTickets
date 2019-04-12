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

        Artisan::call('tikkie:get', []);

        return view('reservation.index', compact('reservations'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tickets = Ticket::all()->sortBy('show_time');
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
        if (isset($last_reservation)) {
            $order_id = $last_reservation->order_id + 1;
        }

        $order_created = false;
        $total = 0;
        foreach ($tickets as $ticket) {
            $name = 'ticket-' . $ticket->id;
            $amount = 'ticket-' . $ticket->id . '-number';
            if (request($name) == 'on') {
                if ((int)request($amount) <= 0) {
                    return redirect('/')->withErrors('Please enter a positive number tickets')->withInput();
                }
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

        Artisan::call('tikkie:create', ['amount' => $total, 'description' => $description, 'order_id' => $order_id]);

        return view('success');
    }

    public function download()
    {
        $reservations = $this->mergeTicketsReservations();
        $csv = array('ID,Name,Email,Ticket,Time,Amount,Paid,Last updated');

        foreach ($reservations as $entry) {
            $csv[] = $entry->id . ',' . $entry->name . ',' . $entry->email . ',' . $entry->type . ',' . $entry->show_time . ',' . $entry->amount . ',' . $entry->paid . ',' . $entry->updated_at;
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
            ->select('reservations.*', 'reservations.name', 'reservations.email', 'tickets.type', 'tickets.show_time', 'reservations.amount', 'reservations.updated_at')
            ->orderBy('reservations.id')
            ->get();
    }
}

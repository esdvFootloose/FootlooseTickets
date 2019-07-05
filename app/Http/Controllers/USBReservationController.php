<?php

namespace App\Http\Controllers;

use App\Mail\ReservationUSBPickup;
use App\usbreservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class USBReservationController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'destroy', 'edit', 'download', 'createNewTikkie', 'pickup', 'reset']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Artisan::call('tikkie:getUSB', []);

        $reservations = usbreservation::all();

        return view('usb.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usb.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email'],
            'amount' => ['required', 'gt:0'],
        ]);

        $order = usbreservation::create($validated);

        $description = 'Showcase Infinity film';
        $total = $request->amount * 2 * 100;
        $order_id = $order->id;

        Artisan::call('tikkie:createUSB', ['amount' => $total, 'description' => $description, 'order_id' => $order_id]);
        return view('success');
    }

    public function createNewTikkie($id)
    {
        $description = 'Showcase Infinity film';

        $reserved_tickets = usbreservation::where('id', $id)->first();
        $total = $reserved_tickets->amount * 2 * 100;

        Artisan::call('tikkie:createUSB', ['amount' => $total, 'description' => $description, 'order_id' => $id]);
        return redirect('/reservations');
    }

    public function pickup($id)
    {
        $reservation = usbreservation::where('id', $id)->first();

        $reservation->picked_up = true;
        $reservation->save();

        return redirect('/reservations/movie');
    }

    public function download()
    {
        $reservations = usbreservation::all();
        $csv = array('ID,Name,Email,Amount,Paid,Picked up,Last updated');

        foreach ($reservations as $entry) {
            $csv[] = $entry->id . ',' . $entry->name . ',' . $entry->email . ',' . $entry->amount . ',' . $entry->paid . ',' . $entry->picked_up . ',' . $entry->updated_at;
        }

        $filename = 'reservations-usb-' . date('d-m-Y') . ".csv";
        $file_path = base_path() . '/' . $filename;
        $file = fopen($file_path, "w+");
        foreach ($csv as $data) {
            fputcsv($file, explode(',', $data));
        }
        fclose($file);

        $headers = ['Content-Type' => 'application/csv'];
        return response()->download($file_path, $filename, $headers);
    }

    public function reset()
    {
        $reservations = usbreservation::where('picked_up', 1)->all();

        foreach ($reservations as $reservation) {
            $reservation->picked_up = 0;
            $reservation->save();
        }
        return redirect('/reservations/movie');
    }
}

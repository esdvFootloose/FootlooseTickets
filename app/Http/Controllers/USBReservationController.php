<?php

namespace App\Http\Controllers;

use App\usbreservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class USBReservationController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'destroy', 'edit', 'download', 'createNewTikkie']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Artisan::call('tikkie:get', []);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email'],
            'amount' => ['required', 'int'],
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
        $total = 0;
        $description = 'Showcase Infinity film';

        $reserved_tickets = Reservation::where('id', $id)->first();
        $total = $reserved_tickets->amount * 2 * 100;

        Artisan::call('tikkie:create', ['amount' => $total, 'description' => $description, 'order_id' => $id]);
        return redirect('/reservations');
    }

    public function download()
    {
        $reservations = usbreservation::all();
        $csv = array('ID,Name,Email,Amount,Paid,Last updated');

        foreach ($reservations as $entry) {
            $csv[] = $entry->id . ',' . $entry->name . ',' . $entry->email . ',' . $entry->amount . ',' . $entry->paid . ',' . $entry->updated_at;
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
}

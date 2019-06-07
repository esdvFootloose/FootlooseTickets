<?php

namespace App\Console\Commands;

use App\Mail\ReservationConfirmed;
use App\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Database\Eloquent;

class FetchTikkie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tikkie:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all the tikkies and their statuses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = base_path() . "/resources/python/";

        $process = new Process("cd {$path} && python3 cli.py");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $paid = json_decode($process->getOutput())->paymentRequests;
        $paid = collect($paid);

        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
            if ($reservation->paid == 0) {
                $external_id = 'ticket-' . $reservation->order_id;
                $tikkie = $paid->where('externalId', '=', $external_id)->first();

                if (!$tikkie) {
                    continue;
                } else {
                    if ($reservation->tikkie_link == null && $tikkie->status == 'OPEN') {
                        $reservation->tikkie_link = "https://tikkie.me/pay/Footloose/" . $tikkie->paymentRequestToken;
                        $reservation->save();
                    }
                    if (!$reservation->tikkie_link == null && $tikkie->status == 'EXPIRED') {
                        $reservation->tikkie_link = null;
                        $reservation->save();
                    }
                }


                if (empty($tikkie->payments)) {
                    continue;
                } else {
                    $payment = collect($tikkie->payments)->where('onlinePaymentStatus', '=', 'PAID')->first();
                    if (!empty($payment)) {
                        $ordered = $reservations->where('order_id', $reservation->order_id);
                        foreach ($ordered as $order) {
                            $order->paid = true;
                            $order->save();
                        }
                        $updated_at = $reservations->first()->updated_at;
                        Mail::to($reservation->email)->send(
                            new ReservationConfirmed($reservation->name, $reservation->order_id, $updated_at)
                        );
                    }
                }

            } else {
                if ($reservation->tikkie_link) {
                    $reservation->tikkie_link = '';
                    $reservation->save();
                }
            }
        }
    }
}

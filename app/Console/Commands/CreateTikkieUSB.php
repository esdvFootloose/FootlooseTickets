<?php

namespace App\Console\Commands;

use App\Http\Controllers\USBReservationController;
use App\Mail\ReservationCreated;
use App\Mail\ReservationUSBCreated;
use App\usbreservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateTikkieUSB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tikkie:createUSB 
    {amount : The amount of the tikkie in cents} 
    {description : The description given to the tikkie} 
    {order_id : The id of the order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a tikkie with the parameters on input';

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
        $amount = $this->argument('amount') + 25;
        $description = $this->argument('description');
        $order_id = $this->argument('order_id');

        $path = base_path()."/resources/python/";

        $process = new Process("cd {$path} && python3 cli.py --mode request --amount {$amount} --description '{$description}' --externalid 'usb-'{$order_id}");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $order = usbreservation::where('id', $order_id)->first();
        $payment_url = json_decode($process->getOutput())->paymentRequestUrl;

        Mail::to($order->email)->send(
            new ReservationUSBCreated($order->name, $order_id, $payment_url)
        );

        return true;
    }
}

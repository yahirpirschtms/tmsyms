<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentPickedUpNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;

    /**
     * Create a new message instance.
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Shipment Picked Up - No Trackers')
                    ->view('emails.shipment_picked_up')
                    ->with([
                        'shipment' => $this->shipment,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Routing\UrlGenerator;
use Datetime;

class SaleNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $sale;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sale)
    {
        $this->sale = $sale;
        $now = new DateTime();
        $this->subject = "NUEVA VENTA {$now->format('Y-m-d H:i:s')}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.saleNotify');
    }
}

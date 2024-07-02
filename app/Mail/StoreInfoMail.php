<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

   
    public function build()
    {

        return $this->from('stores_info@atlbha.com')->subject('stores Inforamation')->view('send_stores_info_template')->with('data', $this->data);

    }
}

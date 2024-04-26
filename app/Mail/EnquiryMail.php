<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryMail extends Mailable
{

    use SerializesModels;

    public $data;
   

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                        ->replyTo($this->data['from_email'], $this->data['from_name'])
                        ->to($this->data['to_email'], $this->data['to_name'])
                        ->subject($this->data['subject'])
                        ->view('emails.enquiryEmail')
                        ->with([
                            'data' => $this->data
                        ]);
    }

}

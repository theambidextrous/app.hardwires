<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInvoice extends Mailable
{
    use Queueable, SerializesModels;

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
        return $this->subject( $this->data->subjectLine )
            ->view('mails.new_invoice')
            ->attach(storage_path($this->data->invoiceAttachment), [
                'as' => 'HardWires_Invoice_no_' . $this->data->invoiceNo . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

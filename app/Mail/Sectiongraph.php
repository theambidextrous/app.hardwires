<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sectiongraph extends Mailable
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
        return $this->subject( $this->data->sectionTitle )
            ->view('mails.section_graph')
            ->attach(storage_path($this->data->userGraph), [
                'as' => 'HardWires_Result_' . $this->data->userRef . '_' . date('Y-m-d H:i:s') . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

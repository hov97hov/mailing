<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

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
        try{
            $this->view('email.email')
                ->from('hov97mkrtchyan@gmail.com')
                ->subject($this->data['subject'])
                ->with([
                    'text' => $this->data['text'] ?? '',
                    'files' => $this->data['files'] ?? ''
                ]);

            return $this;

        }catch (\Exception $exception) {

            return $exception->getMessage();
        }
    }
}

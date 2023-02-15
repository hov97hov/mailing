<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Bus\Queueable;
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

        $settings = Email::where('email',$this->data['from'])->first();
        Config::set('mail.mailers.smtp.host', $settings->host);
        Config::set('mail.mailers.smtp.port', $settings->port);
        Config::set('mail.mailers.smtp.encryption', $settings->encryption);
        Config::set('mail.mailers.smtp.username', $settings->username);
        Config::set('mail.mailers.smtp.password', $settings->password);
        Config::set('mail.mailers.smtp.from', $settings->email);
        Config::set('mail.mailers.smtp.header', $settings->header);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try{
            $this->view('email.'.$this->data['template'])
                ->from($this->data['from'])
                ->subject($this->data['subject'])
                ->with([
                    'data' => $this->data ?? '',
                    'files' => $this->data['file'] ?? ''
                ]);

            $this->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'X-PM-Message-Stream', Config::get('mail.mailers.smtp.header')
                );
            });

            return $this;

        }catch (\Exception $exception) {

            return $exception->getMessage();
        }
    }
}

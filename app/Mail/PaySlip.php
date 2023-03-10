<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class PaySlip extends Mailable
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
        $pdf = PDF::loadView('emails.others.payslip_pdf_template', ['content' => $this->data['pdf_content']])->setPaper('a4', 'portrait')->setOption('margin-bottom', 0)->output();

        return $this
            ->subject($this->data['subject'])
            ->attachData($pdf, Carbon::now()->format('F_Y').'_Payslip'.'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->markdown('emails.others.payslip_mail_template')->with('mail_content', $this->data['mail_content']);
    }
}

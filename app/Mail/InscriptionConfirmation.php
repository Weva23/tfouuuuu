<?php

namespace App\Mail;

use App\Models\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $etudiant;

    public function __construct(Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;
    }

    public function build()
    {
        return $this->subject('Confirmation d\'inscription')
                    ->view('emails.inscription_confirmation');
    }
}

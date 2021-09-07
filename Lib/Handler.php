<?php

namespace AcMarche\Booking\Lib;

class Handler
{
    public function handle(array $data)
    {
        var_dump($data);

        $content = Twig::rendPage('_mail.html.twig', [
            'room' => $data['room'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'informations' => $data['complements'],
            'tva' => $data['tva'],
            'horaires' => $data['horaires'],
        ]);
        $sujet = 'Votre demande de réservation pour LA BOX';
        echo $content;
    }

}

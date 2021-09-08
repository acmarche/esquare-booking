<?php

namespace AcMarche\Booking\Lib;

class Handler
{
    public function handle(array $data)
    {
        var_dump($data);

        $mailer = new Mailer();
        $email = $mailer->createEmail($data, 'Box');
        $mailer->send($email);
    }

}

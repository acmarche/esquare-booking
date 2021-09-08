<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;

class Handler
{
    public function handle(array $data, \WP_Post $post)
    {
        var_dump($data);
        dump($post);

        $roomId = BookingJf::getRoomNumber($post->ID);
        $mailer = new Mailer();
        $email = $mailer->createEmail($data, $post->post_title, $roomId);
        $mailer->send($email);
    }

}

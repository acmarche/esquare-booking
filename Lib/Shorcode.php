<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;

class Shorcode
{
    public function __construct()
    {
        add_shortcode('calendar_jf', function () {

            global $post;
            if (isset($_POST['booking_form'])) {
                $data = $_POST['booking_form'];
                $handler = new Handler();
                $handler->handle($data, $post);
            }

            $dateSelected = new \DateTime();
            $room = BookingJf::getRoomNumber($post->ID);
            $render = new Render();

            return $render->renderBooking($dateSelected, $room);
        });
    }

}

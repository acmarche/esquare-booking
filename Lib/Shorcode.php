<?php

namespace AcMarche\Booking\Lib;

class Shorcode
{
    public function __construct()
    {
        add_shortcode('calendar_jf', function () {
            $twig = new Twig();
            $dateProvider = new DateProvider();
            $weeks = $dateProvider->weeksOfMonth(new \DateTime());

            return $twig->render('_calendar.html.twig', [
                'weeks' => $weeks,
            ]);
        });
    }
}

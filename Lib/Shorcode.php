<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;

class Shorcode
{
    public function __construct()
    {
        add_shortcode('calendar_jf', function () {
            $twig = new Twig();
            $dateProvider = new DateProvider();
            $today = new \DateTime();
            $weeks = $dateProvider->weeksOfMonth($today);
            $entries = $this->getEntries();

            return $twig->render('_calendar.html.twig', [
                'weeks' => $weeks,
                'weekdays' => $dateProvider->weekDaysName(),
                'entries' => $entries,
            ]);
        });
    }

    private function getEntries()
    {
        global $post;
        $post_slug = $post->post_name;
        $room = BookingJf::getRoomNumber($post_slug);
        $repository = new EntryRepository();
        $json = $repository->getEntries($room);

        return json_decode($json);
    }
}

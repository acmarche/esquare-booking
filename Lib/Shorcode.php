<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;

class Shorcode
{
    public function __construct()
    {
        add_shortcode('calendar_jf', function () {
            $dateProvider = new DateProvider();
            $dateSelected = new \DateTime();
            $weeks = $dateProvider->weeksOfMonth($dateSelected);
            $entries = $this->getEntries();
            $monthName = $dateProvider->monthName($dateSelected);

            return Twig::rendPage('_calendar.html.twig', [
                'monthName' => $monthName,
                'weeks' => $weeks,
                'weekdays' => $dateProvider->weekDaysName(),
                'entries' => $entries,
            ]);
        });
    }

    private function getEntries(): array
    {
        global $post;
        $post_slug = $post->post_name;
        $room = BookingJf::getRoomNumber($post_slug);
        $repository = new EntryRepository();
        try {
            $json = $repository->getEntries($room);

            return json_decode($json);

        } catch (\Exception $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur agenda', $e->getMessage());

            return [];
        }
    }
}

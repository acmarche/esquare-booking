<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;
use AcMarche\Booking\Entity\EntryDay;

class Shorcode
{
    public function __construct()
    {
        add_shortcode('calendar_jf', function () {
            $dateProvider = new DateProvider();
            $dateSelected = new \DateTime();
            $repository = new EntryRepository();
            $weeks = $dateProvider->weeksOfMonth($dateSelected);
            global $post;
            $post_slug = $post->post_name;
            $room = BookingJf::getRoomNumber($post_slug);
            $monthEntries = $repository->getEntries($room);
            $dataDays = [];

            foreach ($weeks as $week) {
                foreach ($week as $date) {
                    $dataDay = new EntryDay($date);
                    $entries = $this->extractByDate($date, $monthEntries);
                    $dataDay->entries = $entries;
                    $dataDays[$date->toDateString()] = $dataDay;
                }
            }

            $monthName = $dateProvider->monthName($dateSelected);

            return Twig::rendPage('_calendar.html.twig', [
                'monthName' => $monthName,
                'weeks' => $weeks,
                'weekdays' => $dateProvider->weekDaysName(),
                'dataDays' => $dataDays,
                'room' => $room,
            ]);
        });
    }

    private function extractByDate(\DateTimeInterface $dateTime, array $entries): array
    {
        $data = [];
        foreach ($entries as $entry) {
            if ($entry->startTime === $dateTime->format('Y-m-d')) {
                $data[] = $entry;
            }
        }

        return $data;
    }
}

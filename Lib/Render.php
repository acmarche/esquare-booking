<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\Entity\EntryDay;

class Render
{
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

    public function renderBooking(\DateTimeInterface $dateSelected, int $room): string
    {
        $calendar = $this->renderCalendar($dateSelected, $room);
        $repository = new EntryRepository();
        $form = $repository->getRemoteForm();

        return Twig::rendPage('booking.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    public function renderCalendar(\DateTimeInterface $dateSelected, int $room): string
    {
        $dateProvider = new DateProvider();
        $repository = new EntryRepository();
        $weeks = $dateProvider->weeksOfMonth($dateSelected);
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
            'dateSelected' => $dateSelected,
        ]);
    }
}

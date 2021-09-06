<?php

namespace AcMarche\Booking\Lib;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use DateTimeInterface;

class DateProvider
{
    /**
     * Names of days of the week.
     *
     * @return string[]
     */
    public function weekDaysName(): array
    {
        return ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    }

    /**
     * @return int[]
     */
    public function getHours(): array
    {
        return range(1, CarbonInterface::HOURS_PER_DAY);
    }

    public function daysOfMonth(DateTimeInterface $date): CarbonPeriod
    {
        $carbon = Carbon::instance($date);

        return Carbon::parse($carbon->firstOfMonth())->daysUntil(
            $carbon->endOfMonth()
        );
    }

    /**
     * Retourne la liste des semaines.
     *
     * @return CarbonPeriod[]
     */
    public function weeksOfMonth(DateTimeInterface $date): array
    {
        $weeks = [];
        $firstDayMonth = Carbon::instance($date)->firstOfMonth();

        do {
            $weeks[] = $this->daysOfWeek($firstDayMonth); // point at the end of Week
            $firstDayMonth->nextWeekday(); //passe de dimanche a lundi
        } while ($firstDayMonth->isSameMonth($date));

        return $weeks;
    }

    public function daysOfWeek(CarbonInterface $date): CarbonPeriod
    {
        $firstDayOfWeek = $date->copy()->startOfWeek()->toMutable()->toDateString();
        $lastDayOffWeek = $date->endOfWeek()->toDateString(); //+6

        return Carbon::parse($firstDayOfWeek)->daysUntil($lastDayOffWeek)->locale(
            'fr_FR'
        );
    }
}

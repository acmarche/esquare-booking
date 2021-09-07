<?php

namespace AcMarche\Booking\Entity;

use Carbon\CarbonInterface;

class EntryDay
{
    public array $entries;
    public CarbonInterface $day;

    public function __construct(CarbonInterface $day)
    {
        $this->day = $day;
    }
}

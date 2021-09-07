<?php
/**
 * Plugin Name:     Booking Jf
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     booking-jf
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Booking_Jf
 */

namespace AcMarche\Booking;

use AcMarche\Booking\Lib\Api;
use AcMarche\Booking\Lib\Asset;
use AcMarche\Booking\Lib\Shorcode;

class BookingJf
{
    public static array $rooms = [
        'mon-calendrier' => 1,
        'box' => 1,
        'createive' => 2,
        'meeting' => 3,
        'relax' => 4,
        'digital' => 5,
    ];

    public function __construct()
    {
        new Asset();
        new Shorcode();
        new Api();
    }

    public static function getRoomNumber(string $slug): int
    {
        if (isset(self::$rooms[$slug])) {
            return self::$rooms[$slug];
        }

        return 0;
    }
}

$booking = new BookingJf();

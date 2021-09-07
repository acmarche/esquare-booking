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
        3992 => 1,
        2365 => 1,//box
        2372 => 2,//creative
        2593 => 3,//meeting
        2375 => 4,//relax
        2379 => 5,//digital
    ];

    public function __construct()
    {
        new Asset();
        new Shorcode();
        new Api();
    }

    public static function getRoomNumber(int $postId): int
    {
        if (isset(self::$rooms[$postId])) {
            return self::$rooms[$postId];
        }

        return 0;
    }
}

$booking = new BookingJf();

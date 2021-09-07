<?php

namespace AcMarche\Booking\Lib;

class Asset
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'loadAssets']);
    }

    function loadAssets()
    {
        wp_enqueue_style(
            'marchebe-bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'marchebe-bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );

        wp_enqueue_style(
            'marchebe-calendar',
            plugin_dir_url(__DIR__).'assets/calendar.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'marchebe-calendar-js',
            plugin_dir_url(__DIR__).'assets/calendar.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
}

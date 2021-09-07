<?php


namespace AcMarche\Booking\Lib;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api
 * @package AcMarche\Theme\Inc
 */
class Api
{
    public function __construct()
    {
        if (!is_admin()) {
            $this->api();
        }
    }

    function api()
    {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'booking',
                    'entries/(?P<date>.*+)/(?P<slug>.*+)',
                    [
                        'methods' => 'GET',
                        'callback' => function ($request) {
                            $date = $request->get_param('date');
                            $slug = $request->get_param('slug');
                            $repository = new EntryRepository();
                            $entries = $repository->getEntriesByDay($date, $slug);
                        },
                    ]
                );
            }
        );
    }
}

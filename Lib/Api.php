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
                    'entries/(?P<date>[\w-]+)/(?P<room>[\d]+)',
                    [
                        'methods' => 'GET',
                        'args' => array(
                            'date' => array(
                                'required' => true,
                            ),
                            'room' => array(
                                'required' => true,
                            ),
                        ),
                        'callback' => function ($request) {
                            $date = $request->get_param('date');
                            $room = $request->get_param('room');
                            $repository = new EntryRepository();
                            $entries = $repository->getEntriesByDay($date, $room);

                            return Twig::rendPage('_list.html.twig', ['entries' => $entries]);
                        },
                    ]
                );
            }
        );
    }
}

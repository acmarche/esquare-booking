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
                            array_map(function ($entry) {
                                $entry->startTime = \DateTime::createFromFormat(
                                    'Y-m-d H:i',
                                    $entry->startTime
                                )->format('H:i');
                                $entry->endTime = \DateTime::createFromFormat('Y-m-d H:i', $entry->endTime)->format(
                                    'H:i'
                                );
                            }, $entries);

                            return Twig::rendPage('_list.html.twig', ['entries' => $entries]);
                        },
                    ]
                );
            }
        );

        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'booking',
                    'calendar/(?P<date>[\w-]+)/(?P<room>[\d]+)/(?P<action>[\d]+)',
                    [
                        'methods' => 'GET',
                        'args' => array(
                            'date' => array(
                                'required' => true,
                            ),
                            'room' => array(
                                'required' => true,
                            ),
                            'action' => array(
                                'required' => true,
                            ),
                        ),
                        'callback' => function ($request) {
                            $dateSelected = \DateTime::createFromFormat('Y-m-d', $request->get_param('date'));
                            $room = $request->get_param('room');
                            $action = $request->get_param('action');

                            if ($action == 2) {
                                $dateSelected->modify('+1 month');
                            } else {
                                $dateSelected->modify('-1 month');
                            }

                            $render = new Render();
                            return $render->renderCalendar($dateSelected, $room);
                        },
                    ]
                );
            }
        );
    }
}

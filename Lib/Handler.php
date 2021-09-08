<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class Handler
{
    public function handle(array $data, \WP_Post $post)
    {
        var_dump($data);
        dump($post);

        $roomId = BookingJf::getRoomNumber($post->ID);
        $horaire = BookingJf::horaires[$data['horaire']];

        $id = $this->insertInDb($data, $post->post_title, $roomId, $horaire, $data['horaire']);

        $mailer = new Mailer();
        $email = $mailer->createPublicEmail($data, $post->post_title, $roomId, $horaire, null);
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur booking mail', $e->getMessage());
        }

        $email = $mailer->createAdminEmail($data, $post->post_title, $roomId, $horaire, $id);
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur booking mail', $e->getMessage());
        }
    }

    public function insertInDb(array $data, string $roomName, int $room, string $horaireName, int $horaireId): ?int
    {
        $wpdb = new \wpdb(DB_USER, DB_PASSWORD, 'grr_esquare', DB_HOST);
        $table = 'grr_booking';
        $data['room_id'] = $room;
        $data['room_name'] = $roomName;
        $data['horaire_name'] = $horaireName;
        $data['horaire_id'] = $horaireId;
        $data['done'] = 0;
        unset($data['_token']);
        unset($data['room']);
        unset($data['horaire']);
        $format = [];
        try {
            $wpdb->insert($table, $data, $format);

            return $wpdb->insert_id;
        } catch (\Exception $exception) {
            wp_mail('webmaster@marche.be', 'Esquare erreur booking sql', $exception->getMessage());
        }

        return null;
    }

}

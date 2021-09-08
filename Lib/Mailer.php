<?php

namespace AcMarche\Booking\Lib;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class Mailer
{
    private Transport\TransportInterface $mailer;

    public function __construct()
    {
        Env::loadEnv();
        $this->mailer = Transport::fromDsn($_ENV['MAILER_DSN']);
    }

    public function createPublicEmail(array $data, string $room, int $roomId, string $horaire, ?int $idDb): Email
    {
        $subject = 'Votre demande de réservation pour '.$room;

        $html = $this->generateHtml($data, $room, $subject, $horaire, null);

        return (new TemplatedEmail())
            ->from($_ENV['MAILER_FROM'])
            ->to('webmaster@marche.be')
            //->to($data['email'])
            ->subject($subject)
            ->html($html)
            ->htmlTemplate('_mail.html.twig')
            ->context(
                [
                    'data' => $data,
                    'room' => $room,
                ]
            );
    }

    public function createAdminEmail(array $data, string $room, int $roomId, string $horaire, ?int $idDb)
    {
        $subject = 'Valider la réservation pour '.$room;
        $html = $this->generateHtml($data, $room, $subject, $horaire, $idDb);

        return (new TemplatedEmail())
            ->from($_ENV['MAILER_FROM'])
            ->to($_ENV['MAILER_FROM'])
            ->subject($subject)
            ->html($html)
            ->htmlTemplate('_mail.html.twig')
            ->context(
                [
                    'data' => $data,
                    'room' => $room,
                ]
            );
    }

    private function generateHtml(
        array $data,
        string $room,
        string $subject,
        string $horaire,
        ?int $idDb
    ): string {

        list($year, $month, $day) = explode('-', $data['jour']);
        $jour = $day.'/'.$month.'/'.$year;

        $action_url = null;
        $action_texte = null;
        if ($idDb) {
            $action_url = $_ENV['GRR_BOOKING'].'/id/'.$idDb;
            $action_texte = 'Valider';
        }

        return Twig::rendPage('_mail.html.twig', [
            'data' => $data,
            'room' => $room,
            'importance' => 'high',
            'email' => ['subject' => $subject],
            'action_url' => $action_url,
            'action_text' => $action_texte,
            'exception' => null,
            'horaire' => $horaire,
            'jour' => $jour,
        ]);
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(Email $email)
    {
        $this->mailer->send($email);
    }

}

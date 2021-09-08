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

    public function createEmail(array $data, string $room, int $roomId, string $horaire): Email
    {
        $subject = 'Votre demande de réservation pour '.$room;
        list($year, $month, $day) = explode('-', $data['jour']);
        $jour = $day.'/'.$month.'/'.$year;

        $html = Twig::rendPage('_mail.html.twig', [
            'data' => $data,
            'room' => $room,
            'importance' => 'high',
            'email' => ['subject' => $subject],
            'action_url' => $_ENV['GRR_BOOKING'].'/id/'.$roomId,
            'action_text' => 'Go',
            'exception' => null,
            'horaire' => $horaire,
            'jour' => $jour,
        ]);

        return (new TemplatedEmail())
            ->from($_ENV['MAILER_FROM'])
            ->to('webmaster@marche.be')
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

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(Email $email)
    {
        $this->mailer->send($email);
    }

}
